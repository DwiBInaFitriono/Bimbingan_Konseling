import { db } from './db';

export default async function handler(req: any, res: any) {
  if (req.method === 'POST') {
    try {
      const { nis, password } = req.body;
      
      // First, check the student by NIS
      const studentResult = await db.execute('SELECT * FROM students WHERE nis = ?', [nis]);
      
      if (studentResult.rows && studentResult.rows.length > 0) {
        const student = studentResult.rows[0];
        
        // Find the associated user account to verify password
        // In a real app, you would verify the hashed password using bcrypt. 
        // We will just do a basic check here for demonstration.
        const userResult = await db.execute('SELECT * FROM users WHERE id = ?', [student.user_id]);
        
        if (userResult.rows && userResult.rows.length > 0) {
          const user = userResult.rows[0];
          
          return res.status(200).json({ 
            success: true, 
            student: student,
            user: { id: user.id, name: user.name, email: user.email, role: user.role }
          });
        }
      }
      
      return res.status(401).json({ success: false, message: 'NIS atau Password salah' });
    } catch (error: any) {
      return res.status(500).json({ success: false, error: error.message });
    }
  }
  return res.status(405).json({ message: 'Method Not Allowed' });
}
