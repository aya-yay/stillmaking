<?php 

  function validation($request) { //$_POST連想配列
    $errors = [];
    
    if(empty($request['your_name']) || 20 < mb_strlen($request['your_name'])) {
      $errors[] = 'お名前を入力してください(20文字以内)';
    }

    if(empty($request['email']) || !filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'メールアドレスを正しい形式で入力してください';
    }
    
    if(empty($request['message']) || 200 < mb_strlen($request['message'])) {
      $errors[] = 'メッセージを入力してください(200文字以内)';
    }



    return $errors;
  }

?>