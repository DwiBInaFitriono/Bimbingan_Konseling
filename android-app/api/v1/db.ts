import { connect } from '@tidbcloud/serverless';

let _client: any = null;

function getClient() {
  if (!_client) {
    const connectionString = process.env.DATABASE_URL;
    if (!connectionString || !connectionString.startsWith('mysql://')) {
      throw new Error(
        'DATABASE_URL tidak ditemukan atau tidak valid. Set environment variable DATABASE_URL ' +
        'dengan connection string mysql:// yang valid sebelum menjalankan aplikasi.'
      );
    }
    const finalConnectionString = connectionString.includes('ssl=')
      ? connectionString
      : connectionString + (connectionString.includes('?') ? '&ssl={"rejectUnauthorized":true}' : '?ssl={"rejectUnauthorized":true}');
    _client = connect({ url: finalConnectionString });
  }
  return _client;
}

export const db = {
  execute: async (query: string, params?: any[]) => {
    const client = getClient();
    return client.execute(query, params);
  }
};
