var express = require("express");
var nodemailer = require('nodemailer');
var app = express();


app.use(express.static('public'));



app.use('/script.js', express.static(__dirname + '/script.js'));
app.use('/option.json', express.static(__dirname + '/option.json'));
app.use('/database.json', express.static(__dirname + '/database.json'));

app.get("/", (req, res) => {
    res.sendFile(__dirname + "/mailhtml.html")
    //res.sendFile(__dirname + "/e.php")
})


// app.post("/mail", (req, res) => {
//     console.log("test");
//     //console.log(req.body);
// })


app.get("/mail", (req, res) => {
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
    res.sendFile(__dirname + "/mailhtml.html")
})


app.listen(8080, ()=> {
    console.log("HTTP Server started on port 8080")
})