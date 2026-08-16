import { connect } from '@tidbcloud/serverless';

let connectionString = 'mysql://app_user:app_password@database_host:4000/sistem_bk';

// Validate that process.env.DATABASE_URL is a valid mysql connection string
if (!connectionString || !connectionString.startsWith('mysql://')) {
  connectionString = 'mysql://app_user:app_password@database_host:4000/sistem_bk';
}

// Ensure SSL parameters are appended
if (!connectionString.includes('ssl=')) {
  connectionString += connectionString.includes('?') ? '&ssl={"rejectUnauthorized":true}' : '?ssl={"rejectUnauthorized":true}';
}

export const db = connect({ url: connectionString });
