import { connect } from '@tidbcloud/serverless';

let connectionString = 'mysql://29hkS1jfx2jKirT.root:Uv24c1b0gb2XrzuQ@gateway01.ap-southeast-1.prod.aws.tidbcloud.com:4000/test';

// Validate that process.env.DATABASE_URL is a valid mysql connection string
if (!connectionString || !connectionString.startsWith('mysql://')) {
  connectionString = 'mysql://29hkS1jfx2jKirT.root:Uv24c1b0gb2XrzuQ@gateway01.ap-southeast-1.prod.aws.tidbcloud.com:4000/test';
}

// Ensure SSL parameters are appended
if (!connectionString.includes('ssl=')) {
  connectionString += connectionString.includes('?') ? '&ssl={"rejectUnauthorized":true}' : '?ssl={"rejectUnauthorized":true}';
}

export const db = connect({ url: connectionString });
