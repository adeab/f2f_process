<template>
    <div>
        <!-- <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody v-for="user in users" v-bind:key="user.id">
                <tr>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td><button class="btn btn-success">Make Call</button></td>
                    
                </tr> 
                               
            </tbody>
        </table> -->
        <label>Your ID</label>
        <textarea id="yourId"></textarea><br>
        <label>Other ID</label>
        <textarea id="otherId"></textarea><br>
        <button id="connect">Connect</button>

        <label>Enter Message</label>
        <textarea id="yourMessage"></textarea>
        <button id="send">Send</button>
        <pre id="messages"></pre>

        <button @click="startConnection('test')" class="btn btn-primary">Test Start</button>
    
        
    </div>
</template>
<script>
export default {
    data(){
        return {
            users: [],
            reciever_id: '',
            sender_id: '',
            pagination: {},
            call_created: false
        }
    },
    mounted() {
        let recaptchaScript = document.createElement('script')
        recaptchaScript.setAttribute('src', '../resources/js/bundle.js')
        document.head.appendChild(recaptchaScript)
    },
    created(){
        this.fetchUsers();
        //this.startConnection();
        
    },
    methods:{
        fetchUsers(){
            
            fetch('api/users')
            .then(res => res.json())
            .then(res => {
                this.users= res.data;
            })
            .catch(err => console.log(err));
        },
        startConnection(param){
            navigator.webkitGetUserMedia({video: true, audio: false}, function(stream){
            
            var currentUrl= window.location.href;
            window.location=currentUrl+"#"+param;
            var Peer= require('simple-peer');
            var peer= new Peer({
                initiator: location.hash==="#"+param,
                trickle: false,
                stream: stream
            });
            peer.on('signal', function (data){
                document.getElementById('yourId').value= JSON.stringify(data);
            })
            document.getElementById('connect').addEventListener('click', function () {
                var otherId= JSON.parse(document.getElementById('otherId').value);
                peer.signal(otherId);
            })
            document.getElementById('send').addEventListener('click', function(){
                var yourMessage = document.getElementById('yourMessage').value;
                peer.send(yourMessage)
            })
            peer.on('data', function(data){
                document.getElementById('messages').textContent += data + '\n';
            })
            peer.on('stream', function (stream){
                var video = document.createElement('video');
                document.body.appendChild(video);
                video.srcObject=stream;
                video.play();
            })
            }, function (err){
                console.error(err);
            })
            
        }
        
    }
};
</script>
