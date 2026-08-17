import { connect } from '@tidbcloud/serverless';

let connectionString = process.env.DATABASE_URL || 'mysql://app_user:app_password@database_host:4000/sistem_bk';

// DATABASE_URL wajib disetel lewat environment variable (mis. Vercel Project Settings).
// Jangan pernah menaruh kredensial database langsung di source code.
if (!connectionString || !connectionString.startsWith('mysql://')) {
  throw new Error(
    'DATABASE_URL tidak ditemukan atau tidak valid. Set environment variable DATABASE_URL ' +
    'dengan connection string mysql:// yang valid sebelum menjalankan aplikasi.'
  );
}

// Pastikan parameter SSL ikut disertakan
const finalConnectionString = connectionString.includes('ssl=')
  ? connectionString
  : connectionString + (connectionString.includes('?') ? '&ssl={"rejectUnauthorized":true}' : '?ssl={"rejectUnauthorized":true}');

export const db = connect({ url: finalConnectionString });
