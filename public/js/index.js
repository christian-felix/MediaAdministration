
class Ball {

    dx = 2;
    dy = 4;
    radius = 10;
 
    constructor(x, y) {
       this.x = x;
       this.y = y;
    }
 
    move() {
       this.checkDirection();
       this.x += this.dx;
       this.y += this.dy;
    }
 
    checkDirection(){
       if (this.y < 0) this.dy *= -1;
       if (this.x < 0 || this.x > 700) this.dx *= -1;
    }
 
    setDirection(){
        this.dy *= -1;  
    }
 
    draw(ctx) {
       ctx.beginPath();
       ctx.arc(this.x, this.y, this.radius, 0, Math.PI*2, true); 
       ctx.closePath();
       ctx.fill();
    }
 }
 
 class Paddle {
 
    dx = 5;
    width = 80;
    height = 10;
    left = false;
    right = false;
 
    constructor() {
       this.x = 700/2 - this.width/2;
       this.y = 270;
    }
 
    move(){      
     
       if (this.left == true) {
          this.x -= this.dx;
       } 
 
       if (this.right == true) {
          this.x += this.dx;
       }
    }
 
    bounce(ball) {
       
       if (ball.y >= 270 && ball.x >= this.x && ball.x <= this.x + this.width) {
          ball.setDirection();
       }
    }
 
    draw(ctx){
       ctx.fillStyle = "rgba(100,0,0,1)";
       ctx.beginPath();
       ctx.rect(this.x, this.y, this.width, this.height);
       ctx.closePath();
       ctx.fill();
    }
 }
 
 class Game {
 
    constructor(ctx, ball, paddle){
       this.ctx = ctx;
       this.ball = ball;
       this.paddle = paddle;
    }
 
    init(){
 
       this.ctx.clearRect(0,0,700,300);
       this.ball.move();
       this.ball.draw(this.ctx);
       this.paddle.bounce(this.ball);
       this.paddle.move();
       this.paddle.draw(this.ctx);
 
       if (this.ball.y > 300) {
          clearInterval(intevalId);
       }
    }
 }
 
 function init() {
 
    var ctx = $('#canvas')[0].getContext('2d');
    var ball = new Ball(100,250);
    var paddle = new Paddle();
    ball.setDirection();
    var interval = 10;
    var game = new Game(ctx, ball, paddle);
 
    $(document).keydown((event)=>{      
       if (event.which == 37) {
          paddle.left = true;
       } else if (event.which == 39) {
          paddle.right = true;   
       }
    });
 
    $(document).keyup((event)=>{      
       if (event.which == 37) {
          paddle.left = false;
       } else if (event.which == 39) {
          paddle.right = false;   
       }
    });

    $(document).mousemove((event) => {
      if (event.pageX > 0 && event.pageX < 700) 
      paddle.x = event.pageX;

    });

 
    setInterval(() => {
       game.init()
    }, interval);
 }
 
 
 init();
 