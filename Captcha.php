<?php

class Captcha{

    public static function Sujeira(){
        // Digite abaixo uma frase qualquer para misturar ao hash
        return "Hasta la vista, baby!";
    }

    public static function Crypto($numero){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $sessid = session_id();
        return md5($numero . Captcha::Sujeira() . $sessid);
    }

    public static function Conferir(){
        $numero = empty($_POST["numero"]) ? null : filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING);
        $hash = empty($_POST["hash"]) ? null : filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING);

        if(!empty($hash) && !empty($numero)){
            if(Captcha::Crypto($numero) === $hash){
                return true;
            }
        }
        return false;
    }

    public static function Gerar(){

        $aleatorio = rand(1000, 9999);
        $numero = str_pad(strval($aleatorio), 4, '0', STR_PAD_LEFT);
        $hash = Captcha::Crypto($numero);

        $imagemBase64 = Captcha::GerarImagem($numero);

        if($imagemBase64 !== false){
            echo "
            <style>.box-captcha{padding:15px;background-color:#efeeee;border-radius:5px;margin-top:10px;border:1px solid #ccc}.box-captcha label{margin-bottom:10px;font-size:14px;width:100%}.box-captcha div{display:flex}.box-captcha div img{border-radius:5px;margin:0px;padding:0px;top:0px;border:none;margin-right:10px}.box-captcha div input[type=text]{width:100px;height:50px !important;background-color:#fff;border-radius:5px;border:1px solid #ccc;margin:0px;padding:0px;top:0px;text-align:center;font-size:26px;color:#333}@media only screen and (max-width: 767px){.box-captcha{text-align:center}.box-captcha div{margin:auto;text-align:center}.box-captcha div img{margin:auto}.box-captcha div input[type=text]{margin:auto}}</style>
            <div class='box-captcha'>
            <label>Digite os números da imagem ao lado.</label>
            <div>
                <input type='hidden' name='hash' value='{$hash}'>
                <img src='data:image/png;base64,{$imagemBase64}'>
                <input required type='text' name='numero' autocomplete='off' maxlength=4 pattern='\d{4}' title='Digite o número na imagem ao lado.'>
            </div>
            </div>";
        }
    }

    public static function GerarImagem($numero){

        /*
        Aqui, a imagem é capturada em um buffer de saída usando ob_start(), imagepng(), 
        e ob_get_contents(). Depois, o conteúdo do buffer é convertido em uma string 
        base64 usando base64_encode().
        */

        try {
            # Gerando imagem base
            $imagem = imagecreate(120, 46);
            $background_color = imagecolorallocate($imagem, 130, 130, 130);

            for ($i = 0; $i < 200; $i++){
                # Gerando cores aleatórias para cada linha aleatória
                $cor_linha = imagecolorallocate($imagem, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

                $x1 = mt_rand(-200, 230);
                $x2 = mt_rand(-200, 230);
                $y1 = mt_rand(-200, 230);
                $y2 = mt_rand(-200, 230);
                imageline($imagem, $x1, $y1, $x2, $y2, $cor_linha);
            }

            $font_color = imagecolorallocate($imagem, 175, 175, 175);
            $left = 10;

            $font_list = array(
                "FjallaOne-Regular.ttf",
                "BreeSerif-Regular.ttf",
                "DMSerifDisplay-Regular.ttf",
                "PatuaOne-Regular.ttf"
            );

            # Escolhendo index de uma das fontes da lista
            $font_name = $font_list[1];

            # Gerando números desalinhados.
            imagettftext($imagem, 26, rand(-20,20), ($left), rand(30,43), $font_color, __DIR__ . "/fonts/".$font_name, substr($numero,0,1));
            imagettftext($imagem, 26, rand(-20,20), ($left+35), rand(30,43), $font_color, __DIR__ . "/fonts/".$font_name, substr($numero,1,1));
            imagettftext($imagem, 26, rand(-20,20), ($left+60), rand(30,43), $font_color, __DIR__ . "/fonts/".$font_name, substr($numero,2,1));
            imagettftext($imagem, 26, rand(-20,20), ($left+85), rand(30,43), $font_color, __DIR__ . "/fonts/".$font_name, substr($numero,3,1));

            ob_start();
            imagepng($imagem);
            $imageData = ob_get_contents();
            ob_end_clean();

            imagedestroy($imagem);

            return base64_encode($imageData);

        }catch(\Throwable $th){
            return false;
        }
    }
}

?>