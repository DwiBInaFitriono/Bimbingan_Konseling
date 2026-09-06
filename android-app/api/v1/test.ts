export default function handler(incomingRequest: any, serverResponse: any) {
  return serverResponse.status(200).json({
    success: true,
    message: "Serverless function is working!"
  });
}
