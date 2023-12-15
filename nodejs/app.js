var express = require("express");
const axios = require("axios");
var qs = require("qs");
const http = require("http");
let fs = require('fs');
const IS_PRODUCTION = false;
let PHP_HOST, SOCKET_PORT, prefix;

if (IS_PRODUCTION) {
  PHP_HOST = "https://homei.co.id";
  SOCKET_PORT = 31337;
  prefix = "/";
} else {
  PHP_HOST = "http://localhost";
  SOCKET_PORT = 3000;
  prefix = "/homei/";
}

var app = express();
var server = http.createServer(app);
var io = require("socket.io")(server, {
  cors: {
    origin: PHP_HOST,
  },
});

function write(text, prefix = "") {
  if (typeof text === "object") text = JSON.stringify(text);
  if (prefix != "") text = prefix + " : " + text;
  fs.appendFile('my.log', text + "\n", function (err, data) {
    if (err) {
      return console.log(err);
    }
    console.log(text);
  });
}

io.use(function (socket, next) {
  let url_get_session;
  var sessionId = socket.handshake.query.token,
    bearer = socket.handshake.headers.authorization,
    chat_id = socket.handshake.query.chat_id,
    options = {};

  if (sessionId != undefined) {
    let template = { headers: { Cookie: "PHPSESSID=" + sessionId, } }
    Object.assign(options, template);
    url_get_session = prefix + "web/user/get-session-id?chat_id=" + chat_id;
  } else if (bearer != undefined) {
    let template = { headers: { Authorization: bearer, } }
    Object.assign(options, template);
    url_get_session = prefix + "web/api/v1/user/get-session-id?chat_id=" + chat_id;
  }

  axios
    .post(
      PHP_HOST + url_get_session,
      {},
      options
    )
    .then((res) => {
      return res.data;
    })
    .then((sessionIdFromRequest) => {
      if (
        sessionIdFromRequest !== undefined &&
        sessionIdFromRequest.data !== undefined &&
        (sessionId == sessionIdFromRequest.data.token || bearer == sessionIdFromRequest.data.token)
      ) {
        next();
      } else {
        next(new Error("not authorized 3"));
      }
    })
    .catch((e) => console.log(e.response));
});

var path = require("path");
app.use(express.static(path.join(__dirname, "/public")));

io.on("connection", (socket) => {
  let url_list_chat, url_save_chat, url_read_chat;

  var sessionId = socket.handshake.query.token,
    bearer = socket.handshake.headers.authorization,
    chat_id = socket.handshake.query.chat_id,
    konsultasi_id = socket.handshake.query.konsultasi_id,
    options = {};

  if (sessionId != undefined) {
    let template = {
      Cookie: "PHPSESSID=" + sessionId,
    }
    Object.assign(options, template);
    url_list_chat = prefix + "web/konsultasi/list-chat?ticket=" + chat_id;
    url_save_chat = prefix + "web/konsultasi/save-chat?ticket=" + chat_id;
    url_read_chat = prefix + "web/konsultasi/read-chat?ticket=" + chat_id;
  } else if (bearer != undefined) {
    let template = {
      Authorization: bearer,
    }
    Object.assign(options, template);
    url_list_chat = prefix + "web/api/v1/konsultasi/list-chat?ticket=" + chat_id;
    url_save_chat = prefix + "web/api/v1/konsultasi/save-chat?ticket=" + chat_id;
    url_read_chat = prefix + "web/api/v1/konsultasi/read-chat?ticket=" + chat_id;
  }

  socket.join(chat_id);
  write("new user connected : " + socket.id);

  /**
   * membaca pesan
   */
  axios({
    method: "POST",
    url: PHP_HOST + url_read_chat,
    headers: options,
  })
    .then((res) => {
      write(res.data);
    })
    .catch((e) => write("AXIOS READ CHAT : " + e));

  socket.on("disconnect", () => {
    write(`user ${socket.id} disconnected`);
  });
  socket.on("chat message", (msg) => {
    socket.broadcast.emit("chat message", msg); //sending message to all except the sender
  });

  socket.on("private message", (msg) => {
    axios({
      method: "POST",
      url: PHP_HOST + url_save_chat,
      data: qs.stringify({
        konsultasi_id: konsultasi_id,
        body: msg,
      }),
      headers: options,
    })
      .then((res) => {
        if (res.data.success === true)
          socket.to(chat_id).emit("private message", res.data.data);
      })
      .catch((e) => write("AXIOS : " + e));
  });
});

server.listen(SOCKET_PORT, () => {
  write("Server listening on :" + SOCKET_PORT);
});
