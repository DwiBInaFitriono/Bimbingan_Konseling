export default function handler(req: any, res: any) {
  // Endpoint diagnostik ini sengaja tidak lagi membocorkan status env var
  // atau versi Node (bisa dipakai attacker untuk reconnaissance).
  return res.status(200).json({
    success: true,
    message: "Serverless function is working!"
  });
}
