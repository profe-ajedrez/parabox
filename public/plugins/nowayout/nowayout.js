/**
 * NoWayOut
 *
 * Autor Andrés Reyes a.k.a. Oldman Jake
 * juego de bacteria cuadrada vs bacterias redondas
 */

/**
 * objectHasProperty
 *
 * Función helper que indica si un objeto posee una propiedad.
 * @param {*} obj
 * @param {*} prop
 */
const objectHasProperty = function(obj, prop) {
    'use strict';

    return Object.prototype.hasOwnProperty.call(obj, prop);
};

/**
 * mathHelper
 *
 * Módulo que contiene útiles para cálculos matemáticos
 */
const MathHelper = (function(){
    'use strict';

    function _getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() *
               (max - min + 1)) + min;
    }

    function _getSign(n) {
        if (n>=0) {
            return 1;
        }
        return -1;
    }

    return {

        randomInt  : _getRandomInt,
        signFactor : _getSign
    };
})();

/**
 * GameState
 * Almacena los psibles estados del juego
 */
const GameState = (function(){
    'use strict';

    const _play     = 0,
          _pause    = 1,
          _gameover = 2;

    return {
        PLAY     : _play,
        PAUSE    : _pause,
        GAMEOVER : _gameover
    };
})();

/**
 * ActorState
 *
 * Almacena los posibles estados de los actores.
 */
const ActorState = (function(){
    'use strict';

    const _moving      = 0,
          _freeze      = 1,
          _justCreated = 2,
          _fallingDead = 3,
          _invencible  = 4,
          _mitosis     = 5,
          _hunting     = 6,
          _prey        = 7,
          _idle        = 8,
          _hurt        = 9;
    return {
        PLAYING      : _moving,
        FREEZED     : _freeze,
        FALLINGDEAD : _fallingDead,
        INVENCIBLE  : _invencible,
        MITOSIS     : _mitosis,
        HUNTING     : _hunting,
        PREY        : _prey,
        IDLE        : _idle,
        HURT        : _hurt
    };
})();



/**
 * MovingBehaviour
 *
 * Proporciona los medios básicos para crear un patron de
 * movimiento.
 * dx y dy son los delta, es decir en cuanto se modifica la
 * posición del objeto
 * al moverlo.
 *
 * @param Sprite sprite
 * @param Canvas canvas
 */

class MoveBehaviour {
    constructor(actor) {
        /**
         * Referencia al actor que
         * posee este comportamiento.
         */
        this.ownerActor = actor;

        this.dx = 0;
        this.dy = 0;
    }

    step() {
        this.ownerActor.x += this.dx;
        this.ownerActor.y += this.dy;
        this.ownerActor.directionY = ( this.ownerActor.behaviours.move.dy > 0 ? "DOWN"  : "UP");
        this.ownerActor.directionX = ( this.ownerActor.behaviours.move.dx > 0 ? "RIGHT" : "LEFT");
    }
}

/**
 * BounceBehaviour
 * Extiende a MovingBehaviour,
 * implementando el movimiento rebote
 *
 * Implementa el comportamiento movimiento rebotar
 * @param {*} sprite
 * @param {*} canvas
 */
class BounceBehaviour extends MoveBehaviour{
    constructor(ownerActor) {
        super(ownerActor);
    }

    bounce() {
        const xAxis = (this.ownerActor.xAxis === undefined ?
                       true : this.ownerActor.xAxis);
        const yAxis = (this.ownerActor.yAxis === undefined ?
                       true : this.ownerActor.yAxis);

        switch(this.ownerActor.collisionType) {
            case 1:
                this.ownerActor.behaviours.move.dy *= (yAxis ? -1 : 1 );
                break;
            case 2:
                this.ownerActor.behaviours.move.dx *= (xAxis ? -1 : 1);
                break;
            case 3:
                if (MathHelper.randomInt(1, 4) === 1){
                    this.ownerActor.behaviours.move.dx *= (xAxis ? -1 : 1);
                }
                this.ownerActor.behaviours.move.dy *= (yAxis ? -1 : 1);

            default:
                break;
        }
    }
}


class Sprite {
    constructor(color, canvas) {
        this.color  = color;
        this.canvas = canvas;
        this.x = 0;
        this.y = 0;
        this.color   = color;
        this.context = canvas.getContext("2d");
        this.type    = "";
        /**
         * Placeholder para almacenar futura imagen
         */
        this.img = null;
    }

    get className() {
        return "sprite";
    }

    draw() {
        //Implementada por clases hijas
        //De lo contrario arrojar TypeError
        let errorMsj = (this.className === "Sprite" ?
                        "No se definieron clases hijas de Sprite" : "Sin definir en clase " + this.className);
        throw new TypeError(errorMsj);
    }
}


class Actor extends Sprite {
    constructor(color, canvas) {
        super(color, canvas);
        this.behaviours = {};
        this.behaviours.move = new MoveBehaviour(this);
        this.behaviours.move.dx = 1;
        this.behaviours.move.dy = 1;
        this.directionX = 'RIGHT';
        this.directionY = 'DOWN';
        this.collisionType = 0; //No colision
        this.state = ActorState.PLAYING;
    }

    get className() {
        return "actor";
    }

    isColliding(spriteChild) {
        //Implementada por clases hijas
        //De lo contrario arrojar TypeError
        let errorMsj = (this.className === "actor" ?
                        "No se definieron clases hijas de Actor" : "Sin definir en clase " + this.className);
        throw new TypeError(errorMsj);
    }

    goingOutOfScene() {
        //Implementada por clases hijas
        //De lo contrario arrojar TypeError
        let errorMsj = (this.className === "actor" ?
                        "No se definieron clases hijas de Actor" : "Sin definir en clase " + this.className);
        throw new TypeError(errorMsj);
    }

    collidingCircleVsCircle(circleA, circleB) {
        if (circleA.x + circleA.r + circleB.r > circleB.x &&
            circleA.x < circleB.x + circleA.r + circleB.r &&
            circleA.y + circleB.r + circleB.r > circleB.y &&
            circleA.y < circleB.y + circleA.r + circleB.r)
        {
            const distance = Math.sqrt(
                ((circleA.x - circleB.x) * (circleA.x - circleB.x)) +
                ((circleA.y - circleB.y) * (circleA.y - circleB.y))
            );
            if (distance < circleB.r + circleA.r) {
                this.collisionType = 3; //Colisión aleatoria
                return true;
            }
        }
        return false;
    }

    collidingCircleVsRect(circle, rectangle) {
        let dx = Math.abs(circle.x - rectangle.x - rectangle.w/2);
        let dy = Math.abs(circle.y - rectangle.y - rectangle.h/2);

        if (dx > (rectangle.w/2 + circle.r) ||
            dy > (rectangle.h/2 + circle.r)) {
                this.collisionType = 0; //No colisión
            return false; // no colisiÃ³n
        }

        if (dx <= (rectangle.w/2)) {
            this.collisionType = 1;
            return true; //horizontal colision
        }

        if (dy <= (rectangle.h/2)) {
            this.collisionType = 2;
            return true;  //vertical colision
        }

        dx=dx-rectangle.w/2;
        dy=dy-rectangle.h/2;
        if (dx*dx+dy*dy<=(circle.r * circle.r)) {
            this.collisionType = 3;
            return true; //random colision
        }

        return false;
    }

    collidingRectVsRect(rectA, rectB) {
        return (rectA.x < rectB.x + rectB.w &&
                rectA.x + rectA.w > rectB.x &&
                rectA.y < rectB.y + rectB.h &&
                rectA.h + rectA.y > rectB.y);
    }
}


/**
 * Enemy
 *
 * clase Placeholder para usar como base para
 * futuras clases que la hereden.
 */
class Enemy extends Actor {
    constructor(color, canvas) {
        super(color, canvas);
    }

    get className() {
        return "enemy";
    }
}


class CircleBacteria extends Enemy {
    constructor(color, canvas) {
        super(color, canvas);
        this.r    = 2.5;
        this.type = "bacteria";
        this.behaviours.bounce = new BounceBehaviour(this);
        this.behaviours.bounce.dx = 1;
        this.behaviours.bounce.dy = 1;
    }

    get className() {
        return "bacteria";
    }

    draw(hookCallbackBefore, hookCallbackAfter) {
        if (hookCallbackBefore &&
            typeof hookCallbackBefore === 'function') {
            hookCallbackBefore();
        }

        this.context.beginPath();
        this.context.arc(this.x,
                         this.y,
                         this.r,
                         0,
                         2 * Math.PI,
                         false);
        this.context.lineWidth   = 3;
        this.context.strokeStyle = this.color;
        this.context.stroke();

        if (hookCallbackAfter &&
            typeof hookCallbackAfter === 'function') {
                hookCallbackAfter();
        }
    }

    isColliding(actor) {
        //Si actor tiene radio es un circulo (o elipse)
        if (objectHasProperty(actor, 'r')) {
            //collidingCircleVsCircle definida en clase ancestro Actor
            return this.collidingCircleVsCircle(this, actor);
        }
        //Si actor tiene ancho y alto, es rectangulo (o cuadrado, o trapezoid)
        if (objectHasProperty(actor, 'w') &&
            objectHasProperty(actor, 'h')) {
                //collidingCircleVsRect definida en clase ancestro Actor
                return this.collidingCircleVsRect(this, actor);
        }
        //Si actor tiene base y altura, es triangulo
        if (objectHasProperty(actor, 'b') &&
            objectHasProperty(actor, 'h')) {
                //TODO crear clase triangulo
                //TODO crear Bool collidingCircleVsTriangle
                //TODO crear Bool collidingRectVsTriangle
                //TODO crear Bool collidingTriangleVsTriangle
            return false;
        }
    }

    goingOutOfScene() {
        const bounce = this.behaviours.bounce ;
        if (this.x + bounce.dx > this.canvas.width - this.r  ||
            this.x + bounce.dx < this.r) {
            this.collisionType = 2;
            //bounce.dx = -bounce.dx;
            return true;
        }

        if (this.y + bounce.dy > this.canvas.height - this.r ||
            this.y + bounce.dy < this.r) {
            //bounce.dy = -bounce.dy;
            this.collisionType =1;
            return true;
        }
        this.collisionType = 0;
        return false;
    }
}



class RedBall extends CircleBacteria {
    constructor (canvas) {
        super("#c0392b", canvas);
        this.y = MathHelper.randomInt(
                    this.r * 2,
                    canvas.height - this.r * 2
                 );
        this.x = MathHelper.randomInt(
                    this.r * 2,
                    canvas.width - this.r * 2
                 );
        this.behaviours.move.dx =
        (MathHelper.randomInt(1,2) ? 1 : -1 );
        this.behaviours.move.dy =
        (MathHelper.randomInt(1,2) ? 1 : -1 );

        this.yAxis = true;
        this.xAxis = true;
    }

    get className() {
        return "redball";
    }
}

class GreenBall extends CircleBacteria {
    constructor (canvas) {
        super("#1abc9c", canvas);
        this.r = 5;

        if (MathHelper.randomInt(1,2) == 1) {
            this.y       = this.r;
            this.x       = MathHelper.randomInt(this.r, canvas.width - this.r);
            this.behaviours.move.dy = 1;
            this.behaviours.move.dx = 0;
            this.yAxis   = true;
            this.xAxis   = false;
        } else {
            this.x       = this.r;
            this.y       = MathHelper.randomInt(this.r, canvas.height - this.r);
            this.behaviours.move.dx = 1;
            this.behaviours.move.dy = 0;
            this.yAxis   = false;
            this.xAxis   = true;
        }
    }

    get className() {
        return "greenball";
    }
}


class OrangeBall extends CircleBacteria {
    constructor(canvas) {
        super("#f39c12", canvas);
        this.r = 3;
        this.x = ( MathHelper.randomInt(1,2) ? this.r +2 : this.canvas.width - this.r -2);
        this.y = MathHelper.randomInt(this.r +2, canvas.height - this.r -2);
        this.behaviours.move.dx =  1;
        this.behaviours.move.dy = -1;
        this.fixedy  = this.y;
        this.range   = 15;
        this.angle   = 0;
        this.angleSpeed = 0.10;

        //Reescribimos el método step de MoveBehaviour para que se comporte con movimiento sinusoidal propio de OrangeBall
        this.behaviours.move.step = function() {
            this.ownerActor.x     += this.ownerActor.behaviours.move.dx;
            this.ownerActor.angle += this.ownerActor.angleSpeed;
            this.ownerActor.fixedy *= (this.ownerActor.collisionType == 1 ? -1: 1);
            this.ownerActor.y = this.ownerActor.fixedy +
                            Math.sin(this.ownerActor.angle) *
                            this.ownerActor.range;
        };
    }

    get className() {
        return "orangeball";
    }
}


class RectangleActor extends Actor {
    constructor(color, canvas) {
        super(color, canvas);
        this.w = 0;
        this.h = 0;
    }

    get className() {
        return "rectangleactor";
    }

    isColliding(actor) {
        if (objectHasProperty(actor, 'r')) {
            return this.collidingCircleVsRect(actor, this);
        }
        if (objectHasProperty(actor, 'w') &&
            objectHasProperty(actor, 'h')) {
            return this.collidingRectVsRect(this, actor);
        }
        //TODO add collision triangle
        return false;
    }

    goingOutOfScene() {
        return ((this.x + this.w > this.canvas.width)  ||
               (this.x < 0) ||
               (this.y + this.h > this.canvas.height) ||
               (this.y < 0));
    }


    draw(hookCallbackBefore, hookCallbackAfter) {
        if (hookCallbackBefore &&
            typeof hookCallbackBefore === 'function') {
            hookCallbackBefore(this);
        }

        this.context.beginPath();
        this.context.lineWidth = "1";
        this.context.fillStyle = this.color;
        this.context.fillRect(this.x, this.y, this.w, this.h);

        if (hookCallbackAfter &&
            typeof hookCallbackAfter === 'function') {
                hookCallbackAfter(this);
        }
    }
}


/**
 * Player
 *
 */

class Player extends RectangleActor {

    constructor(color, canvas) {
        super(color, canvas);
        this.w = 8;
        this.h = 8;
        this.behaviours.move.dx = 1;
        this.behaviours.move.dy = 1;
        this.x = canvas.width - 16;
        this.y = canvas.height / 2;
        this.lastX = this.x;
        this.lastY = this.y;
        this.lives = 5;
    }

    step(keys) {
        if (keys.left === true) {
            this.behaviours.move.dx = -3;
        } else if (keys.right === true) {
            this.behaviours.move.dx =  3;
        } else {
            this.behaviours.move.dx =  0;
        }

        if (keys.up === true) {
            this.behaviours.move.dy = -3;
        } else if (keys.down === true) {
            this.behaviours.move.dy =  3;
        } else {
            this.behaviours.move.dy =  0;
        }

        if (this.goingOutOfScene()) {
            this.x =  this.lastX;
            this.y =  this.lastY;
        } else {
            this.lastX = this.x;
            this.lastY = this.y;
        }

        this.behaviours.move.step();
    }

    hookBeforeDraw(_self) {
        switch(_self.state) {
            case ActorState.HURT:
                _self.color = "#d35400";
                break;
            case ActorState.PLAYING:
                _self.color = "#3498db";
                break;
            default:
                break;
        }
    }
}


const Settings = (function(){
    'use strict';
    const _parentElementName = "canvas-container",
          _uiElementName     = "ui",
          _height            = 256,
          _width             = 512,
          _platformHeight    =   8,
          _platformWidth     =   8,
          _colorPlatform     = "#9b59b6",
          _factorX           =  16,
          _factorY           =   8,
          _livesWord         = "proteinas",
          _enemyWord         = "microorganismos",
          _uiElement         = "span",
          _uiAttributes      = "",
          _stretchFactor     = 2;

    function _p()     { return _parentElementName; }
    function _h()     { return _height;            }
    function _w()     { return _width;             }
    function _pfw()   { return _platformWidth;     }
    function _pfh()   { return _platformHeight;    }
    function _clp()   { return _colorPlatform;     }
    function _ui()    { return _uiElementName;     }
    function _xfac()  { return _factorX;           }
    function _yfac()  { return _factorY;           }
    function _lword() { return _livesWord;         }
    function _eword() { return _enemyWord;         }
    function _uie()   { return _uiElement;         }
    function _uia()   { return _uiAttributes;      }
    function _sf()    { return _stretchFactor;     }

    return {
        parent         : _p,
        height         : _h,
        width          : _w,
        platformHeight : _pfh,
        platformWidth  : _pfw,
        colorPlatform  : _clp,
        ui             : _ui,
        factorX        : _xfac,
        factorY        : _yfac,
        livesWord      : _lword,
        enemyWord      : _eword,
        uiElement      : _uie,
        uiAttributes   : _uia,
        stretchFactor  : _sf
    };
})();


/**
 * Game
 *
 */
const Game = (function(Settings, GameState) {
    'use strict';

    const _parent  = document.getElementById(Settings.parent()),
          _canvas  = document.createElement("canvas"),
          _context = _canvas.getContext("2d"),
          _ui      = document.getElementById(Settings.ui());
    /**
     * Almacena una referencia al tiempo pasado como parametro por windows.requestframe
     */
    let _time      = 0,
        _cicles    = 0,
        _platforms = [],
        _enemies   = [],
        _allies    = [], //Para futura implementación de multiplayer

        _player,

        _keypressed = {
            right : false,
            left  : false,
            up    : false,
            down  : false
        },
        _state = GameState.PLAY,

       /**
        * Basado en la notaciÃ³n Fehn del ajedrez.
        * El plan del escenario es un string conteniendo caracteres separados por /
        * cada segmento separado por / representa una fila en la grilla de la escena.
        * donde los números representan = cantidad de espacios vacios, y las x representan un espacio
        * a pintar, para ser ocupado por una plataforma.
        * tener en cuenta que, para efectos de diseñar escenas, la grilla es de 32x32.
        */
        _plan   = "32/" +
                  "6xx24/" +
                  "3xxx5xxx3xxxxxxxxxx3xx/" +
                  "3x8x4xxx4xxx5/" +
                  "3x8x4xxx4xxx5/" +
                  "32/"+
                  "12xxx3xx5xx3xx/"+
                  "32/" +
                  "32/" +
                  "8xxx2xx3xxx4xx5/" +
                  "8xxx1xxx3xxx4xx4x/" +
                  "x8xx9x4xx4x/"+
                  "32/" +
                  "32/" +
                  "32/" +
                  "32/"+
                  "32/" +
                  "32/" +
                  "32/"+
                  "3x8x4xxx4xxx5/" +
                  "3x8x4xxx4xxx5/" +
                  "6xx24/" +
                  "6xx24/" +
                  "3xxx5xxx3xxxxxxxxxx3xx/" +
                  "32/" +
                  "32/" +
                  "3x8x4xxx4xxx5/" +
                  "32/" +
                  "16xxxx12/" +
                  "xxxx8xxxx10xxxxxx/" +
                  "xx2xx5x4xx4xx8xxxxx4/"+
                  "32xxxxxxxxx/";

    function _keyDownHandler(e) {
        switch(e.keyCode) {
            case 39:
                _keypressed.right = true;
                break;
            case 37:
                _keypressed.left  = true;
                break;
            case 38:
                _keypressed.up    = true;
                break;
            case 40:
                _keypressed.down  = true;
                break;
            case 80:   //P    A.F.K.
                if (_state === GameState.PAUSE) {
                    _state = GameState.PLAY;
                } else if (_state === GameState.PLAY ) {
                    _state = GameState.PAUSE;
                }
                break;
            default:
                break;
        }
    }

    function _keyUpHandler(e) {
        switch(e.keyCode) {
            case 39:
                _keypressed.right = false;
                break;
            case 37:
                _keypressed.left  = false;
                break;
            case 38:
                _keypressed.up    = false;
                break;
            case 40:
                _keypressed.down  = false;
                break;
            default:
                break;
        }
    }

    /**
     * _generatePlatforms
     *
     * Toma un plan de escena y genera las plataformas para implementarlo.
     * @param String  plan
     */
    function _generatePlatforms(plan) {
        let p, x, xx;

        plan.split("/").forEach(function(fila, indexOfFila){
            x = 0;
            fila.split(/(\d+)/).forEach(function(casilla){
                xx = casilla * 1;
                if (!isNaN(xx)) {
                    x += xx * Settings.factorX();

                } else {
                    p = new RectangleActor(Settings.colorPlatform(), _canvas);
                    p.x = x;
                    p.y = indexOfFila * Settings.factorY();
                    p.w = Settings.platformWidth() *
                          casilla.length *
                          Settings.stretchFactor();

                    p.h = Settings.platformHeight();
                    p.color = Settings.colorPlatform();
                    _platforms.push(p);
                }
            });
        });
    }

    function _drawPlatforms() {
        _platforms.forEach( function(platform){
            platform.draw();
        });
    }

    function _drawBalls(context) {
        _enemies.forEach(function(enemy){
            enemy.draw();
        });
    }

    function _ballFactory(){
        //Cada 5 pelotas, generar una verde.
        if ((_enemies.length +6) % 5 === 0) {
            return new GreenBall(_canvas);
        }

        // 1/10 posibilidades de generar una naranja
        if (MathHelper.randomInt(1, 10) === 4) {
            return new OrangeBall(_canvas);
        }

        //Por defecto generar una roja
        return new RedBall(_canvas);
    }

    function _clearCanvas() {
        _context.clearRect(0, 0, _canvas.width, _canvas.height);
    }

    function _updateBalls() {
        _enemies.forEach(function(enemy){
            enemy.behaviours.move.step();
        });
    }

    function _updateUi(timeStamp) {
        _ui.innerHTML = "<span class='rem12 center mono text-white'>" +
                        _player.lives + " proteinas |" +
                        Math.floor(timeStamp/1000) + " segundos |" +
                        _enemies.length + " bacterias</span>";
    }

    function _checkCollisions() {
        _enemies.forEach(function(enemy, indexOfEnemy){
            if (enemy.goingOutOfScene()) {
                enemy.behaviours.bounce.bounce();
            }
            _platforms.forEach( function(rectangle){
                if (enemy.isColliding(rectangle)) {
                    enemy.behaviours.bounce.bounce();
                }
            });

            //Si te tocan las bacterias...
            if (enemy.isColliding(_player) != 0) {
                if (_player.state === ActorState.PLAYING) {
                    _player.lives   -= 1;
                    _player.state    = ActorState.HURT;
                    _player.hurtTime = _time;
                }
                enemy.behaviours.bounce.bounce();
            }
        });

        _platforms.forEach(function(platform) {
		    if (_player.isColliding(platform)) {
			    _player.x = _player.lastX;
			    _player.y = _player.lastY;
			}
        });
    }

    function _init() {
        document.addEventListener("keydown", _keyDownHandler, false);
        document.addEventListener("keyup",   _keyUpHandler, false);

        _canvas.height    = Settings.height();
        _canvas.width     = Settings.width();

        _parent.appendChild(_canvas);

        _generatePlatforms(_plan);
        _enemies.push(_ballFactory());
        _drawPlatforms();
        _drawBalls();
        _player = new Player("#3498db", _canvas);
    }

    function _update(timeStamp) {
        if (_state === GameState.PLAY) {
            _time = Math.floor(timeStamp / 1000);
            _updateBalls();
            _player.step(_keypressed);
            _clearCanvas();

            _updateUi(timeStamp);

            _drawPlatforms();
            _drawBalls();
            _player.draw(_player.hookBeforeDraw);

            _checkCollisions();
            _cicles++;
            if (((_cicles  / 32)  % 8) === 0) {
                _enemies.push(_ballFactory());
            }

            //Check player states.
            //TODO mover a method, usar switch
            if (_player.state === ActorState.HURT) {
                if (_time - _player.hurtTime >= 3) {
                    _player.hurtTime = 0;
                    _player.state = ActorState.PLAYING;
                }
            }
            if (_player.lives <= 0) {
                _player.state = ActorState.FALLINGDEAD;
                _state = GameState.GAMEOVER;
                _updateUi(timeStamp);
            }
        }

        if (_state === GameState.GAMEOVER) {
            //TODO
        }
        window.requestAnimationFrame(_update);
    }

    function _loop() {
        window.requestAnimationFrame(_update);
    }

    function _gameTime()  { return (_time ? _time : 0 ); }
    function _gameState() { return (_state? _state: -1); }

    return {
        init      : _init,
        gameLoop  : _loop,
        gameTime  : _gameTime,
        gameState : _gameState
    };
})(Settings, GameState);


window.onload = function() {
    'use strict';
    Game.init();
    Game.gameLoop();
};