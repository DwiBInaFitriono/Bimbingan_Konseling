export default function handler(req: any, res: any) {
  return res.status(200).json({ 
    success: true, 
    message: "Serverless function is working!",
    env: {
      hasDbUrl: !!process.env.DATABASE_URL,
      nodeVersion: process.version
    }
  });
}
