var express = require('express');
var app = express();
var http = require('http').Server(app);
var phpExpress = require('php-express')({
    binPath: 'php' // chemin vers l'exécutable PHP sur votre système
});
var io = require('socket.io')(http);

app.engine('php', phpExpress.engine);
app.set('views', './views');
app.set('view engine', 'php');

app.all(/.+\.php$/, phpExpress.router);

// Vos autres routes ici
app.get("/", function(req, res){
    res.render('index.php'); // utilise res.render pour exécuter le fichier PHP
});

io.on('connection', function(socket){
    console.log('a user is connected');
    socket.on('disconnect', function (){
        console.log('a user is disconnected');
    })
})

http.listen(3000, function(){
    console.log('Server listening on port 3000');
});
