import crypto from 'node:crypto';

const TOKEN_EXPIRATION_SECONDS = 60 * 60 * 24;

export interface AuthPayload {
  sub: number;
  role: 'siswa';
  iat: number;
  exp: number;
}

function resolveAuthSecret(): string {
  const configuredSecret = process.env.AUTH_TOKEN_SECRET;
  if (!configuredSecret || configuredSecret.length < 16) {
    if (process.env.NODE_ENV === 'production') {
      console.warn('AUTH_TOKEN_SECRET belum diset di Vercel Project Settings, menggunakan fallback secret.');
    }
    return 'sistembk_default_secure_auth_token_secret_2026';
  }
  return configuredSecret;
}

function encodeBase64Url(inputData: Buffer | string): string {
  return Buffer.from(inputData as any).toString('base64url');
}

export function signToken(studentId: number): string {
  const tokenSecret = resolveAuthSecret();
  const currentTimestampSeconds = Math.floor(Date.now() / 1000);
  const tokenPayload: AuthPayload = {
    sub: studentId,
    role: 'siswa',
    iat: currentTimestampSeconds,
    exp: currentTimestampSeconds + TOKEN_EXPIRATION_SECONDS,
  };
  const payloadBase64 = encodeBase64Url(JSON.stringify(tokenPayload));
  const hmacSignature = crypto.createHmac('sha256', tokenSecret).update(payloadBase64).digest();
  return `${payloadBase64}.${encodeBase64Url(hmacSignature)}`;
}

export function verifyToken(authTokenString: string): AuthPayload | null {
  try {
    const tokenSecret = resolveAuthSecret();
    const [payloadBase64, signatureBase64] = authTokenString.split('.');
    if (!payloadBase64 || !signatureBase64) return null;

    const expectedSignatureBase64 = encodeBase64Url(
      crypto.createHmac('sha256', tokenSecret).update(payloadBase64).digest()
    );
    const providedBuffer = Buffer.from(signatureBase64);
    const expectedBuffer = Buffer.from(expectedSignatureBase64);
    if (providedBuffer.length !== expectedBuffer.length || !crypto.timingSafeEqual(providedBuffer, expectedBuffer)) {
      return null;
    }

    const decodedPayload: AuthPayload = JSON.parse(Buffer.from(payloadBase64, 'base64url').toString('utf8'));
    if (!decodedPayload.exp || decodedPayload.exp < Math.floor(Date.now() / 1000)) return null;

    return decodedPayload;
  } catch {
    return null;
  }
}

export function getAuthenticatedStudentId(incomingRequest: any): number | null {
  const authorizationHeader = incomingRequest.headers?.authorization || incomingRequest.headers?.Authorization;
  if (!authorizationHeader || typeof authorizationHeader !== 'string' || !authorizationHeader.startsWith('Bearer ')) {
    return null;
  }
  const extractedToken = authorizationHeader.slice(7).trim();
  const verifiedPayload = verifyToken(extractedToken);
  return verifiedPayload ? verifiedPayload.sub : null;
}
