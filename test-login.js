const https = require('https');
const options = {
  hostname: 'bimbingan-konseling-three.vercel.app',
  path: '/debug',
  method: 'GET'
};
const req = https.request(options, (res) => {
  let data = '';
  res.on('data', d => data += d);
  res.on('end', () => {
    console.log(data);
    const obj = JSON.parse(data);
    const token = obj.session_token;
    const cookie = res.headers['set-cookie'] ? res.headers['set-cookie'].map(c => c.split(';')[0]).join('; ') : '';
    console.log('Got cookie:', cookie);
    
    setTimeout(() => {
    // Attempt POST to /debug
    const postData = 'email=rdxrio45%40gmail.com&password=password&_token=' + token;
    const postOptions = {
      hostname: 'bimbingan-konseling-three.vercel.app',
      path: '/debug',
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Content-Length': Buffer.byteLength(postData),
        'Cookie': cookie
      }
    };
    const postReq = https.request(postOptions, (postRes) => {
        let postBody = '';
        postRes.on('data', d => postBody += d);
        postRes.on('end', () => {
            console.log('POST status:', postRes.statusCode);
            console.log(postBody);
        });
    });
    postReq.write(postData);
    postReq.end();
    }, 2000); // 2 second delay
  });
});
req.end();

