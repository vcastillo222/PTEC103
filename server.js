/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */
/*
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');

const{ApolloServer,gql} = require('apollo-server-express');

const Usuario = require('./models/usuario');

mongoose.connect('mongodb://localhost.27017/bdunab');

const typeDefs = gql`
type Usuario{
    id: ID!
    nombre: String! 
    pass: String!
}
input UsuarioInput{
    nombre: String!
    pass: String!}
type Response{
    Status: String
    message: String
}

type Query{
    getUsuarios: [Usuario]
    getUsuarioById(id: ID!): Usuario
}
type Mutation{
    addUsuario(input: UsuarioInput): Usuario
    updUsuario(id: ID!, input: UsuarioInput): Usuario 
    delUsuario(id: ID!): Response 
}
`;

const resolvers = {
    Query: {
        async getUsuarios(obj){
            const usuarios = await Usuario.find();
            return usuarios; 
        },
        async getUsuarioById(obj,{id}){
            const usuarioBus = await Usuario.findById(id);
            if(usuarioBus == null){
                return null;
            } else {
                return usuarioBus;
            }
        }
    }, 
    Mutation: {
        async addUsuario(obj,{input}){
            const usuario = new Usuario(input);
            await usuario.save();
            return usuario; 
        },
        async updUsuario(ojb,{id,input}){
            const usuario = await Usuario.findByIdAndUpdate(id,input);
            return usuario; 
        },
        async delUsuario(obj,{id}){
            await Usuario.deleteOne({_id: id});
            return {
                status : "200",
                message: "Usuario Eliminado"
            }
        }
        
    }
    
}

let apolloServer = null; 

const corsOptions = {
    origin: "http://localhost:8090",
    credentials: false
};

async function startServer(){
    apolloServer = new ApolloServer({typeDefs, resolvers, corsOptions});
    await apolloServer.start();
    apolloServer.applyMiddleware({app, cors: false});
}


startServer();

const app = express(); 

app.use(cors());
app.listen(8090,function(){
    console.log("servidor iniciado");
});

*/

const express = require("express");
const mysql = require("mysql2");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Connect to MySQL
const db = mysql.createConnection({
  host: "localhost",
  user: "your_mysql_user",
  password: "your_mysql_password",
  database: "forumdb"
});

db.connect(err => {
  if (err) throw err;
  console.log("✅ Connected to MySQL!");
});

// API to get messages
app.get("/messages", (req, res) => {
  db.query("SELECT * FROM messages ORDER BY created_at DESC", (err, results) => {
    if (err) throw err;
    res.json(results);
  });
});

// API to post a new message
app.post("/messages", (req, res) => {
  const { content } = req.body;
  db.query("INSERT INTO messages (content) VALUES (?)", [content], (err, result) => {
    if (err) throw err;
    res.json({ id: result.insertId, content });
  });
});

app.listen(3000, () => console.log("🚀 Server running on http://localhost:3000"));
