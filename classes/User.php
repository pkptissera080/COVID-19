<?php 
    class User implements JsonSerializable {

        public function __construct()
        {
        }

        public function getUser_id(){
            return $this->user_id;
        }

        public function setUser_id($user_id){
            $this->user_id = $user_id;
        }

        public function getFname(){
            return $this->fname;
        }
    
        public function setFname($fname){
            $this->fname = $fname;
        }
    
        public function getLname(){
            return $this->lname;
        }
    
        public function setLname($lname){
            $this->lname = $lname;
        }
    
        public function getEmail(){
            return $this->email;
        }
    
        public function setEmail($email){
            $this->email = $email;
        }

        public function getType(){
            return $this->type;
        }
    
        public function setType($type){
            $this->type = $type;
        }

        /**
         * @inheritDoc
         */
        public function jsonSerialize()
        {
            $json = array();
            foreach($this as $key => $value) {
                $json[$key] = $value;
            }
            return $json; // or json_encode($json)
        }
    }