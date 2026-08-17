// Helper token otentikasi untuk endpoint serverless.
// Sengaja tidak memakai library JWT eksternal (mengurangi risiko supply-chain
// dan dependency tambahan) — cukup pakai HMAC-SHA256 bawaan Node.js.
//
// Format token: base64url(payloadJson) + "." + base64url(HMAC-SHA256(payloadJson))
//
// WAJIB set environment variable AUTH_TOKEN_SECRET di Vercel Project Settings
// sebelum deploy (minimal 16 karakter acak, misalnya hasil `openssl rand -hex 32`).

import crypto from 'node:crypto';

const TOKEN_TTL_SECONDS = 60 * 60 * 24; // token berlaku 24 jam

export interface AuthPayload {
  sub: number; // student id
  role: 'siswa';
  iat: number;
  exp: number;
}

function requireSecret(): string {
  const secret = process.env.AUTH_TOKEN_SECRET;
  if (!secret || secret.length < 16) {
    throw new Error(
      'AUTH_TOKEN_SECRET belum diset atau terlalu pendek (minimal 16 karakter). ' +
      'Set environment variable AUTH_TOKEN_SECRET di Vercel Project Settings sebelum deploy.'
    );
  }
  return secret;
}

function base64url(input: Buffer | string): string {
  return Buffer.from(input as any).toString('base64url');
}

export function signToken(studentId: number): string {
  const secret = requireSecret();
  const now = Math.floor(Date.now() / 1000);
  const payload: AuthPayload = { sub: studentId, role: 'siswa', iat: now, exp: now + TOKEN_TTL_SECONDS };
  const payloadB64 = base64url(JSON.stringify(payload));
  const sig = crypto.createHmac('sha256', secret).update(payloadB64).digest();
  return `${payloadB64}.${base64url(sig)}`;
}

export function verifyToken(token: string): AuthPayload | null {
  try {
    const secret = requireSecret();
    const [payloadB64, sigB64] = token.split('.');
    if (!payloadB64 || !sigB64) return null;

    const expectedSig = base64url(crypto.createHmac('sha256', secret).update(payloadB64).digest());
    const provided = Buffer.from(sigB64);
    const expected = Buffer.from(expectedSig);
    if (provided.length !== expected.length || !crypto.timingSafeEqual(provided, expected)) {
      return null;
    }

    const payload: AuthPayload = JSON.parse(Buffer.from(payloadB64, 'base64url').toString('utf8'));
    if (!payload.exp || payload.exp < Math.floor(Date.now() / 1000)) return null;

    return payload;
  } catch {
    return null;
  }
}

// Ambil student id dari header Authorization: Bearer <token>.
// Return null jika tidak ada token / token tidak valid / kedaluwarsa.
export function getAuthenticatedStudentId(req: any): number | null {
  const header = req.headers?.authorization || req.headers?.Authorization;
  if (!header || typeof header !== 'string' || !header.startsWith('Bearer ')) return null;
  const token = header.slice(7).trim();
  const payload = verifyToken(token);
  return payload ? payload.sub : null;
}
