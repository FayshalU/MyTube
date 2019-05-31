<?php
    class VideoDetails{

        private $con;

        public function __construct($con){
            $this->con = $con;
        }

        public function createUploadForm(){
            $categories = $this->category();
            return '<form action="uploadVideo.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Choose file to upload</label>
                            <input type="file" name="uploadFile" class="form-control-file" id="exampleFormControlFile1" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Give a title</label>
                            <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="Title" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Give a description</label>
                            <textarea name="description" class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect2">Privacy</label>
                            <select name="privacy" class="form-control" id="exampleFormControlSelect2" required>
                                <option value="0">Public</option>
                                <option value="1">Private</option>
                            </select>
                        </div>
                        '.$categories.'
                        <button type="submit" class="btn btn-primary" name="uploadBtn">Upload</button>
                    </form>';
        }

        private function category(){
            $query = $this->con->prepare("Select * from categories");
            $query->execute();

            $str = '<div class="form-group">
                        <label for="exampleFormControlSelect3">Select a Category</label>
                        <select name="category" class="form-control" id="exampleFormControlSelect3" required>';

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $str .= '<option value="'.$row["id"].'">'.$row["name"].'</option>';
            }

            $str .= '</select>
                    </div>';

            return $str;
        }
    }
?>