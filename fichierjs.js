var express = require("express");
var app = express();
app.use(express.static('public'));
app.get("/", (req, res) => {
    //res.sendFile(__dirname + "/site.html")
    res.sendFile(__dirname + "/e.php")
})

app.listen(8080, ()=> {
    console.log("HTTP Server started on port 8080")
})