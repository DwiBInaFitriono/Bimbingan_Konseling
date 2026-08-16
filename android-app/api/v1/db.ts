import { connect } from '@tidbcloud/serverless';

// Default connection string from your BK project environment.
// In Vercel, it is recommended to put this in Environment Variables (DATABASE_URL)
const connectionString = process.env.DATABASE_URL || 'mysql://app_user:app_password@database_host:4000/sistem_bk?ssl={"rejectUnauthorized":true}';

export const db = connect({ url: connectionString });
