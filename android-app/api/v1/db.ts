import { connect } from '@tidbcloud/serverless';

// Default connection string from your BK project environment.
// In Vercel, it is recommended to put this in Environment Variables (DATABASE_URL)
const connectionString = process.env.DATABASE_URL || 'mysql://29hkS1jfx2jKirT.root:RTpfTnYqsilcW7aS@gateway01.ap-southeast-1.prod.aws.tidbcloud.com:4000/test?ssl={"rejectUnauthorized":true}';

export const db = connect({ url: connectionString });
