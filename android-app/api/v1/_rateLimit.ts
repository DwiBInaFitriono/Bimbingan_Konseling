// Rate limiter in-memory sederhana untuk endpoint serverless.
//
// CATATAN PENTING: instance serverless (Vercel) bisa cold-start dan scale ke
// banyak instance, jadi Map ini TIDAK dibagi antar instance. Ini adalah
// proteksi best-effort untuk memperlambat automated brute-force pada
// instance yang sedang "warm", bukan proteksi definitif.
//
// Untuk proteksi brute-force yang kuat secara global, gunakan penyimpanan
// terpusat seperti Upstash Redis (@upstash/ratelimit) atau layer WAF/CDN.

interface Bucket {
  count: number;
  resetAt: number;
}

const buckets = new Map<string, Bucket>();

// Bersihkan bucket kedaluwarsa secara berkala agar Map tidak membesar terus.
function cleanup(now: number) {
  if (buckets.size < 500) return;
  for (const [key, bucket] of buckets) {
    if (bucket.resetAt < now) buckets.delete(key);
  }
}

export function checkRateLimit(key: string, limit: number, windowMs: number): boolean {
  const now = Date.now();
  cleanup(now);

  const bucket = buckets.get(key);
  if (!bucket || bucket.resetAt < now) {
    buckets.set(key, { count: 1, resetAt: now + windowMs });
    return true;
  }
  if (bucket.count >= limit) return false;
  bucket.count++;
  return true;
}

export function getClientIp(req: any): string {
  const fwd = req.headers?.['x-forwarded-for'];
  if (typeof fwd === 'string' && fwd.length > 0) return fwd.split(',')[0].trim();
  return req.socket?.remoteAddress || 'unknown';
}
