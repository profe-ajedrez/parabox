/**
 * _Request.js
 */

/**
 * _Request
 *
 * Tiene la responsabilidad de encapsular el objeto XmlHttpRequest
 * para realizar peticiones Ajax. Si se omite el parametro options,
 * usa configuración por defecto, si se omite alguna de las propiedades
 * de options, obtiene la correspondiente desde la configuracion por
 * defecto.
 *
 * Compatible desde IE9 en adelante.
 *
 * @author Andrés Reyes
 *
 * @param {Json} options objeto js con la siguiente estructura:
*             {
*                 url       :  String  url contra cuál se realizará la petición.
*                                          Si el método es GET, url debe contener uri encoded los
*                                          datos de la petición.
*                 method    :  String  método de la petición HTTP
*                 header    :  Json
*                              {
*                                  content  : String
*                                  type     : String forma en que se enviaran los datos
*                              }
*                 async               : Bool     Indica si la petición es asyncrona
*                 whenReady           : Function función que se ejecutará cuando está lista la petición
*                 whenNotInitialized  : Function función que se ejecutará mientras
*                                                no se inicialice la petición
*                 whenConnected       : Function función que se ejecutará al momento
*                                                de establecer conexión
*                 whenReceived        : Function función que se ejecutará cuando
*                                                el server reciba los datos enviados
*                 whenProcessing      : Function función que se ejecutara mientras
*                                                el server procesa la petición
*                 useDefaultBehaviour : Bool     Si verdadero, usa funciones por defecto. Si falso,
*                                                no hara uso de las funciones por defecto.
*             }
*/
var _Request = function(options, undefined) {
    /**
     * _self  almacena una referencia al objeto, para usarla cuando el contexto pueda ser confuso.
     */
    var _self = this;
    /**
     * _defaultConfig  contiene configuraciones por defecto para nuestro objeto.
     */
    var _defaultConfig = {
        url    : '',
        method : 'POST',
        header : {
            content : "Content-Type",
            type    : "application/x-www-form-urlencoded"
        },
        async              : true,
        user               : null,
        password           : null,

        /**
         * whenReady
         *
         * Función que se ejecutará cuando la respuesta del servidor este lista, si no se ha provisto una
         * en el parámetro options
         *
         * @param  Object request el objeto XMLHttpRequest que porta nuestra petición
         * @return void
         */
        whenReady          : function(request) {
            if (_self.useDefaultBehaviour) {
                alert('Listo!');
            }
        },

        /**
         * whenNotInitialized
         *
         * Función que se ejecutará mientras no se inicialice la petición
         *
         * @param  Object request el objeto XMLHttpRequest que porta nuestra petición
         * @return void
         */
        whenNotInitialized : function(request) {
            if (_self.useDefaultBehaviour) {
                alert('no Inicializado aún');
            }
        },

        /**
         * whenConnected
         *
         * Función que se ejecutará mientras no se inicialice la petición
         *
         * @param  Object request el objeto XMLHttpRequest que porta nuestra petición
         * @return void
         */
        whenConnected      : function(request) {
            if (_self.useDefaultBehaviour) {
                alert('Conexión establecida');
            }
        },

        /**
         * whenReceived
         *
         * Función que se ejecutará cuando el server reciba los datos enviados
         *
         * @param  Object request el objeto XMLHttpRequest que porta nuestra petición
         * @return void
         */
        whenReceived       : function(request) {
            if (_self.useDefaultBehaviour) {
                alert('El servidor ha recibido la petición');
            }
        },

        /**
         * whenProcessing
         *
         * Función que se ejecutara mientras el server procesa la petición
         *
         * @param  Object request el objeto XMLHttpRequest que porta nuestra petición
         * @return void
         */
        whenProcessing     : function(request) {
            if (_self.useDefaultBehaviour) {
                alert('Procesando la petición');
            }
        },

        /**
         * useDefaultBehaviour Si es true, se usarán las funciones de la configuración por defecto,
         *                     si es false, no se ejecutarán.
         */
        useDefaultBehaviour: true
    };

    /* Verifica si options está definido, en caso contrario, usa configuraciones por defecto */
    options = (options == undefined ? _defaultConfig : options);

    this.url      = (options.url    || _defaultConfig.url);
    this.method   = (options.method || _defaultConfig.method);
    this.async    = (options.async !== undefined ? options.async : _defaultConfig.async);
    this.user     = (options.user ? options.user : _defaultConfig.user);
    this.password = (options.password ? options.password : _defaultConfig.password);

    this.useDefaultBehaviour = (options.useDefaultBehaviour !== undefined ?
                                options.useDefaultBehaviour  : _defaultConfig.useDefaultBehaviour);

    options.header       = (options.header || _defaultConfig.header);
    this.header          = (options.header || _defaultConfig.header);
    this.header.content  = (options.header.content ? options.header.content : undefined);
    this.header.type     = (options.header.type    ? options.header.type    : undefined);

    this.whenReady = (options.whenReady || _defaultConfig.whenReady);

    this.request   = new XMLHttpRequest();

    /*
    Establece controlador de cambio de estados de propiedad readyState.
    Si bien se ofrece está funcionalidad, nada impide que crees una propia y sobreescribas
    si lo consideras necesario.
    */
    this.request.onreadystatechange = function() {

        switch(this.readyState) {
            case 4:
                switch (this.status) {
                    case 200:
                        if (typeof _self.whenReady === 'function') {
                            _self.whenReady(_self.request);
                        }
                        break;
                    case 400:
                        this.error = '400 Bad Request. El request no pudo ser entendido por el servidor debido a sintaxis incorrecta. Revise su url';
                        break;
                    case 401:
                        this.error = '401 Unauthorized. El request requiere autenticación de usuario';
                        break;
                    case 403:
                        this.error = '403 Forbbiden. El servidor entendió el request, pero se niega a responder.';
                        break;
                    case 404:
                        this.error = '404 No encontrdo. El servidor no encontró la url';
                        break;
                    case 500:
                        this.error = '500 error del servidor. Algo pasó en el servidor.';
                        break;
                    default:
                        break;
                }
                break;
            case 0: //request no inicializado
                (options.whenNotInitialized || _defaultConfig.whenNotInitialized)(this.request);
                break;
            case 1: //server coneción establecida
                (options.whenConnected || _defaultConfig.whenConnected)(this.request);
                break;
            case 2: //request recibido
                (options.whenReceived || _defaultConfig.whenReceived)(this.request);
                break;
            case 3: //procesando request
                (options.whenProcessing || _defaultConfig.whenProcessing)(this.request);
                break;
            default:
                break;
        }
    };
};

/**
 * _Request.prototype.open
 *
 * Tiene la responsabilidad de establecer una conexión entre el cliente y el servidor que recibirá
 * la petición Ajax
 *
 * @return void
 */
_Request.prototype.open = function(undefined) {
    this.request.open(this.method, this.url, this.async);
    return this;
};

/**
 * _Request.prototype.send
 *
 * Tiene la responsabilidad de envíar los datos desde el cliente al servidor usando la
 * conexión establecida en el método open()
 *
 * Si el request es de método POST, el parámetro body es un json conteniendo los datos
 * a envíar, incluyendo la identificación de la petición
 *
 * @param  mixed body JSON si el método es post, null si el método es GET.
 * @return void
 */
_Request.prototype.send = function(body, undefined) {

    if (typeof this.method == 'string') {
        if (this.method === 'POST') {
            encodedBody = 'body=' + JSON.stringify(body);
            this.request.setRequestHeader(
                this.header.content,
                this.header.type
            );
        }

        if (this.method === 'GET') {
            encodedBody = null;
        }

        this.request.send(encodedBody);
    }
    return this;
};