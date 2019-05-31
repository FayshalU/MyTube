<?php

    class ButtonProvider{

        public static function createButton($text, $imageSrc, $actionSent, $class){

            $image = ($imageSrc == null) ? "" : "$imageSrc";
            $action = User::isLoggedIn() ? $actionSent : "notSignedIn()";

            return "<button class='$class' onclick='$action'>
                        $image
                        <span class='text'>$text</span>
                    </button>";
        }

        public static function createHref($text, $imageSrc, $href, $class){

            $image = ($imageSrc == null) ? "" : "$imageSrc";

            return "<a href='$href'>
                        <button class='$class'
                            $image
                            <span class='text'>$text</span>
                        </button>
                    </a>";
        }

        public static function createProfileButton($con, $username){

            $user = new User($con, $username);
            $picture = $user->getPicture();
            $link = "profile.php?username=$username";

            return "<a href='$link'>
                        <img src='$picture' class='profilePicture'>
                    </a>";
        }

        public static function editVideoButton($videoId){

            $link = "editVideo.php?videoId=$videoId";

            $button = ButtonProvider::createHref("EDIT VIDEO", null, $link, "edit button");

            return "<div class='editVideoButtonContainer'>
                        $button
                    </div>";
        }

        public static function createSubscriberButton($con, $userTo, $user){

            $userName = $userTo->getUsername();
            $userlogged = $user->getUsername();

            $isSubscribed = $user->isSubscribedTo($userName);

            $buttonText = $isSubscribed ? "SUBSCRIBED" : "SUBSCRIBE";
            $buttonText .= " ". $userTo->getSubscriberCount();

            $buttonClass = $isSubscribed ? "unsubcribe button" : "subscribe button";
            $action = "subscribe(\"$userName\", \"$userlogged\", this)";

            $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

            return "<div class='subscribeButtonContainer'>
                        $button
                    </div>";
        }
    }

?>