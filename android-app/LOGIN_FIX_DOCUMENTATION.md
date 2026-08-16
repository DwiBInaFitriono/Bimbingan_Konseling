# Login System Fix Documentation

## Masalah yang Ditemukan

Error asli: **"Unexpected token 'A', 'A server e'... is not valid JSON"**

### Akar Masalah

1. **Missing Dependency**: Package `@tidbcloud/serverless` tidak ada di `package.json`, menyebabkan serverless function crash saat deploy ke Vercel
2. **No API Proxy**: Vite dev server tidak memiliki proxy untuk route `/api/*`, sehingga request gagal di development
3. **Poor Error Handling**: `ApiService` tidak memeriksa content-type atau HTTP status, langsung parse JSON bahkan saat server return HTML error page
4. **No Password Verification**: `auth.ts` tidak pernah memverifikasi password, hanya cek apakah user exists

## Perbaikan yang Diterapkan

### 1. ✅ Tambahkan Dependency TiDB Serverless
**File**: [`package.json`](package.json:14)

```json
"dependencies": {
  "@tidbcloud/serverless": "^0.3.0",
  "react": "^19.0.0",
  "react-dom": "^19.0.0"
}
```

### 2. ✅ Perbaiki Error Handling di API Service
**File**: [`src/services/api.ts`](src/services/api.ts:6)

Menambahkan fungsi `handleResponse()` yang:
- Cek content-type response (harus JSON)
- Parse JSON response
- Throw error jika response tidak OK (4xx/5xx)
- Memberikan pesan error yang jelas dari server

```typescript
async function handleResponse(res: Response) {
  const contentType = res.headers.get('content-type');
  
  if (!contentType || !contentType.includes('application/json')) {
    const text = await res.text();
    throw new Error(text || 'Server mengembalikan respons non-JSON');
  }
  
  const data = await res.json();
  
  if (!res.ok) {
    throw new Error(data.message || data.error || `HTTP error ${res.status}`);
  }
  
  return data;
}
```

### 3. ✅ Tambahkan Vite Proxy untuk Development
**File**: [`vite.config.ts`](vite.config.ts:37)

```typescript
server: {
  // ... existing config
  proxy: {
    '/api': {
      target: process.env.VITE_API_TARGET || 'http://localhost:3000',
      changeOrigin: true,
      secure: false,
    },
  },
}
```

**Cara Kerja**:
- Di development: proxy ke backend API server (default `localhost:3000`)
- Di production (Vercel): serverless functions handle langsung

### 4. ✅ Perbaiki Password Verification
**File**: [`api/v1/auth.ts`](api/v1/auth.ts:1)

Perubahan:
- Validasi input (nis & password tidak boleh kosong)
- **Verifikasi password** dengan `user.password === password`
- Tambahkan error logging
- Pesan error lebih informatif

```typescript
// Verify password - compare with plain text for now
// TODO: In production, use bcrypt.compare(password, user.password)
if (user.password === password) {
  return res.status(200).json({ 
    success: true, 
    message: 'Login berhasil',
    student: student,
    user: { id: user.id, name: user.name, email: user.email, role: user.role }
  });
}
```

### 5. ✅ Konfigurasi Vercel Serverless
**File**: [`vercel.json`](vercel.json:1)

```json
{
  "functions": {
    "api/**/*.ts": {
      "runtime": "nodejs20.x"
    }
  },
  "rewrites": [
    {
      "source": "/api/v1/(.*)",
      "destination": "/api/v1/$1"
    }
  ]
}
```

## Cara Testing

### Testing di Development (Lokal)

1. **Set up backend API server** (jika belum berjalan):
   ```bash
   # Pastikan ada server yang berjalan di localhost:3000
   # atau set environment variable:
   export VITE_API_TARGET=http://your-backend-url
   ```

2. **Jalankan Vite dev server** (sudah berjalan di port 8443):
   ```bash
   npm run dev
   ```

3. **Test login**:
   - Buka app di preview panel
   - Masukkan NIS (contoh: `2024001`) 
   - Masukkan Password
   - Klik "Masuk Sekarang"

### Testing di Production (Vercel)

1. **Deploy ke Vercel**:
   ```bash
   vercel deploy
   ```

2. **Set environment variable** di Vercel Dashboard:
   - `DATABASE_URL`: Connection string TiDB Cloud
   
3. **Test login** di URL deployment Vercel

## Data Test

Untuk testing, pastikan database memiliki data:

```sql
-- Example test data
INSERT INTO users (id, name, email, password, role) 
VALUES (1, 'Siswa Test', 'siswa@test.com', 'password123', 'student');

INSERT INTO students (id, nis, user_id, name, class) 
VALUES (1, '2024001', 1, 'Siswa Test', 'X RPL 1');
```

**Credentials untuk testing**:
- NIS: `2024001`
- Password: `password123`

## Catatan Keamanan

⚠️ **PENTING untuk Production**:

1. **Password Hashing**: Saat ini password disimpan dan dibandingkan dalam plaintext. Untuk production, gunakan bcrypt:
   ```typescript
   import bcrypt from 'bcrypt';
   
   // Saat registrasi
   const hashedPassword = await bcrypt.hash(password, 10);
   
   // Saat login
   const isValid = await bcrypt.compare(password, user.password);
   ```

2. **JWT Token**: Implementasi JWT untuk session management:
   ```typescript
   import jwt from 'jsonwebtoken';
   
   const token = jwt.sign(
     { userId: user.id, studentId: student.id },
     process.env.JWT_SECRET,
     { expiresIn: '7d' }
   );
   ```

3. **Environment Variables**: Jangan commit `DATABASE_URL` atau credentials lainnya

4. **Rate Limiting**: Tambahkan rate limiting untuk mencegah brute force attack

## Struktur Flow Login

```
┌─────────────┐
│ LoginScreen │
│ (Frontend)  │
└──────┬──────┘
       │ 1. User input NIS & password
       │ 2. Click "Masuk Sekarang"
       ▼
┌──────────────┐
│ ApiService   │
│ .login()     │
└──────┬───────┘
       │ 3. POST /api/v1/auth
       │    { nis, password }
       ▼
┌──────────────┐
│ Vite Proxy   │  (development only)
│ → localhost  │
└──────┬───────┘
       │
       ▼
┌──────────────┐
│ auth.ts      │
│ (Serverless) │
└──────┬───────┘
       │ 4. Query database
       │ 5. Verify password
       │ 6. Return user data
       ▼
┌──────────────┐
│ handleResponse│
│ (Error check)│
└──────┬───────┘
       │ 7. Parse JSON
       │ 8. Check success
       ▼
┌──────────────┐
│ LoginScreen  │
│ Success!     │
│ → Dashboard  │
└──────────────┘
```

## Files Modified

1. ✅ [`package.json`](package.json:1) - Added `@tidbcloud/serverless` dependency
2. ✅ [`src/services/api.ts`](src/services/api.ts:1) - Fixed error handling
3. ✅ [`vite.config.ts`](vite.config.ts:1) - Added API proxy
4. ✅ [`api/v1/auth.ts`](api/v1/auth.ts:1) - Fixed password verification
5. ✅ [`vercel.json`](vercel.json:1) - Created Vercel configuration

## Troubleshooting

### Error: "Server mengembalikan respons non-JSON"
- **Penyebab**: Backend API tidak running atau URL salah
- **Solusi**: Pastikan `VITE_API_TARGET` sudah diset atau backend running di `localhost:3000`

### Error: "NIS atau Password salah"
- **Penyebab**: Credentials salah atau data tidak ada di database
- **Solusi**: Cek data di database, pastikan NIS dan password benar

### Error: "Terjadi kesalahan server"
- **Penyebab**: Database connection error atau serverless function crash
- **Solusi**: Cek `DATABASE_URL` environment variable dan TiDB Cloud connection

### Development: API tidak terpanggil
- **Penyebab**: Vite dev server belum restart setelah perubahan config
- **Solusi**: Restart Vite dev server (`npm run dev`)

## Next Steps

- [ ] Implementasi bcrypt untuk password hashing
- [ ] Tambahkan JWT token authentication
- [ ] Implementasi refresh token mechanism
- [ ] Tambahkan rate limiting
- [ ] Tambahkan logging untuk security audit
- [ ] Setup automated testing untuk login flow
