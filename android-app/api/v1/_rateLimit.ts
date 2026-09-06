interface RateLimitBucket {
  count: number;
  resetAt: number;
}

const rateLimitBuckets = new Map<string, RateLimitBucket>();

function cleanupExpiredBuckets(currentTimestamp: number) {
  if (rateLimitBuckets.size < 500) return;
  for (const [bucketKey, bucketData] of rateLimitBuckets) {
    if (bucketData.resetAt < currentTimestamp) rateLimitBuckets.delete(bucketKey);
  }
}

export function checkRateLimit(
  rateLimitIdentifier: string,
  maxAllowedAttempts: number,
  windowDurationMilliseconds: number
): boolean {
  const currentTimestamp = Date.now();
  cleanupExpiredBuckets(currentTimestamp);

  const activeBucket = rateLimitBuckets.get(rateLimitIdentifier);
  if (!activeBucket || activeBucket.resetAt < currentTimestamp) {
    rateLimitBuckets.set(rateLimitIdentifier, {
      count: 1,
      resetAt: currentTimestamp + windowDurationMilliseconds,
    });
    return true;
  }
  if (activeBucket.count >= maxAllowedAttempts) return false;
  activeBucket.count++;
  return true;
}

export function getClientIp(incomingRequest: any): string {
  const forwardedForHeader = incomingRequest.headers?.['x-forwarded-for'];
  if (typeof forwardedForHeader === 'string' && forwardedForHeader.length > 0) {
    return forwardedForHeader.split(',')[0].trim();
  }
  return incomingRequest.socket?.remoteAddress || 'unknown';
}
