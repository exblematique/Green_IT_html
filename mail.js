function SENDMAIL(){
    alert();
var nodemailer = require('nodemailer');
var transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'greenhightea@gmail.com',
        pass: '7WjTgoLS7WjTgoLS'
    }
    });

    var mailOptions = {
    from: 'greenhightea@gmail.com',
    to: 'tdepreux.ir2021@esaip.org',
    subject: 'Sending Email using Node.js',
    text: 'That was easy!'
    };

    transporter.sendMail(mailOptions, function(error, info){
    if (error) {
        console.log(error);
    } else {
        console.log('Email sent: ' + info.response);
    }
});
}