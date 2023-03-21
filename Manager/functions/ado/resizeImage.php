<?php



/**
 * Efetua o redimensionamento de uma imagem conforme os parametros de
 * configuração.
 *
 * @param       string      $absoluteSystemPathToOriginalImage
 *              Caminho completo até a imagem.
 *
 * @param       string      $absoluteSystemPathToNewImage
 *              Caminho completo até o local onde a nova  imagem será armazenada.
 *
 * @param       string      $resizeType
 *              Tipo de ajuste que será feito.
 *              Os seguintes valores são aceitos:
 *              exact         : redimenciona a imagem exatamente na medida definida em
 *                              "$imgMaxWidth" e "$imgMaxHeight".
 *                              Se um deste valores não for definido, manterá o valor inicial da imagem.
 *
 *              portrait      : redimensiona (verticalmente) a imagem parando quando encontra chegar na
 *                              altura máxima definida em "$imgMaxHeight".
 *
 *              landscape     : redimensiona (horizontalmente) a imagem parando quando encontra chegar na
 *                              largura máxima definida em "$imgMaxWidth".
 *
 *              auto          : redimensiona a imagem até que uma das dimensões encontre um dos valores
 *                              máximos definidos por "$imgMaxWidth" e "$imgMaxHeight".
 *
 * @param       ?int        $imgMaxWidth
 *              Largura máxima que a imagem deverá ter.
 *              Se não for definido, este valor será calculado conforme o tipo "$resizeType".
 *
 * @param       ?int        $imgMaxHeight
 *              Altura máxima que a imagem deverá ter.
 *              Se não for definido, este valor será calculado conforme o tipo "$resizeType".
 *
 * @param       ?bool       $imgCrop
 *              Quando "true", irá, após redimencionar a imagem, efetuar um crop(corte) na imagem resultante
 *              e salvará este corte ao invés da imagem como um todo.
 *              Para evitar que um crop exceda os limites de uma imagem que será redimencionada por um
 *              método dinâmico (portrair | landscape | auto) é recomendavel, mas não obrigatório,
 *              que esta opção seja usada em conjunto com o método "exact".
 *
 * @param       ?int        $imgCropWidth
 *              Largura do crop que será feito.
 *              Apenas surte efeito se "$resizeType" for definido como "crop".
 *
 * @param       ?int        $imgCropHeight
 *              Altura do crop que será feito.
 *              Apenas surte efeito se "$resizeType" for definido como "crop".
 *
 * @param       ?int        $imgCropX
 *              Posição no eixo X a partir de onde o corte da imagem deve ocorrer.
 *              Apenas surte efeito se "$resizeType" for definido como "crop".
 *
 * @param       ?int        $imgCropY
 *              Posição no eixo Y a partir de onde o corte da imagem deve ocorrer.
 *              Apenas surte efeito se "$resizeType" for definido como "crop".
 *
 * @return      bool
 */
function resizeImage(
    $absoluteSystemPathToOriginalImage,
    $absoluteSystemPathToNewImage,
    $resizeType,
    $imgMaxWidth = null,
    $imgMaxHeight = null,
    $imgCrop = null,
    $imgCropWidth = null,
    $imgCropHeight = null,
    $imgCropX = null,
    $imgCropY = null
) {


    $r = false;


    // @type {Resource} Objeto que representa a imagem que será modificada.
    $imageOriginal = false;

    // @type {Resource} Objeto que representa a imagem após as alterações.
    $imageFinal = null;

    // @type {Resource} Objeto que representa a imagem cropada.
    $imageCropped = null;





    $ext = strtolower(strrchr($absoluteSystemPathToOriginalImage, "."));
    switch ($ext) {
        case ".jpg":
        case ".jpeg":
            $imageOriginal = @imagecreatefromjpeg($absoluteSystemPathToOriginalImage);
            break;

        case ".gif":
            $imageOriginal = @imagecreatefromgif($absoluteSystemPathToOriginalImage);
            break;

        case ".png":
            $imageOriginal = @imagecreatefrompng($absoluteSystemPathToOriginalImage);
            break;

        default:
            $imageOriginal = false;
            break;
    }





    // Se não for possível carregar a imagem...
    if ($imageOriginal == false) {
        $msg = "Could not load the target image. File \"" . $absoluteSystemPathToOriginalImage . "\" .";
        throw new Exception($msg);
    } else {
        $imageOriginalWidth     = imagesx($imageOriginal);
        $imageOriginalHeight    = imagesy($imageOriginal);

        $imgMaxWidth            = ($imgMaxWidth == null) ? $imageOriginalWidth : $imgMaxWidth;
        $imgMaxHeight           = ($imgMaxHeight == null) ? $imageOriginalHeight : $imgMaxHeight;

        $imgCropWidth           = ($imgCropWidth == null) ? $imageOriginalWidth : $imgCropWidth;
        $imgCropHeight          = ($imgCropHeight == null) ? $imageOriginalHeight : $imgCropHeight;
        $imgCropX               = ($imgCropX == null) ? 0 : $imgCropX;
        $imgCropY               = ($imgCropY == null) ? 0 : $imgCropY;

        $imageFinalWidth        = null;
        $imageFinalHeight       = null;



        // Efetua calculos para encontrar as novas dimensões da imagem
        switch ($resizeType) {
            case "exact":
                $imageFinalWidth    = $imgMaxWidth;
                $imageFinalHeight   = $imgMaxHeight;
                break;

            case "portrait":
                $imageFinalWidth    = (int)($imgMaxHeight * ($imageOriginalWidth / $imageOriginalHeight));
                $imageFinalHeight   = $imgMaxHeight;
                break;

            case "landscape":
                $imageFinalWidth    = $imgMaxWidth;
                $imageFinalHeight   = (int)($imgMaxWidth * ($imageOriginalHeight / $imageOriginalWidth));
                break;

            case "auto":
                // Calcula a imagem como se fosse "portrait"
                if ($imageOriginalHeight > $imageOriginalWidth) {
                    $imageFinalWidth    = (int)($imgMaxHeight * ($imageOriginalWidth / $imageOriginalHeight));
                    $imageFinalHeight   = $imgMaxHeight;
                } // Calcula a imagem como se fosse "landscape"
                elseif ($imageOriginalHeight < $imageOriginalWidth) {
                    $imageFinalWidth    = $imgMaxWidth;
                    $imageFinalHeight   = (int)($imgMaxWidth * ($imageOriginalHeight / $imageOriginalWidth));
                } // Se a imagem é quadrada...
                else {
                    // Adapta imagem "landscape"
                    if ($imgMaxHeight > $imgMaxWidth) {
                        $imageFinalWidth    = (int)($imgMaxHeight * ($imageOriginalWidth / $imageOriginalHeight));
                        $imageFinalHeight   = $imgMaxHeight;
                    } // Adapta imagem "portrait"
                    elseif ($imgMaxHeight < $imgMaxWidth) {
                        $imageFinalWidth    = $imgMaxWidth;
                        $imageFinalHeight   = (int)($imgMaxWidth * ($imageOriginalHeight / $imageOriginalWidth));
                    } // Adapta imagem para as novas dimensões
                    else {
                        $imageFinalWidth = $imgMaxWidth;
                        $imageFinalHeight = $imgMaxHeight;
                    }
                }
                break;
        }




        // Recria a imagem com as dimensões calculadas
        $imageFinal = imagecreatetruecolor($imageFinalWidth, $imageFinalHeight);
        imagecopyresampled(
            $imageFinal,
            $imageOriginal,
            0,
            0,
            0,
            0,
            $imageFinalWidth,
            $imageFinalHeight,
            $imageOriginalWidth,
            $imageOriginalHeight
        );




        // Se for para "cropar"
        if ($imgCrop) {
            // Efetua o corte.
            $imageCropped = imagecreatetruecolor($imgCropWidth, $imgCropHeight);
            imagecopyresampled(
                $imageCropped,
                $imageFinal,
                0,
                0,
                $imgCropX,
                $imgCropY,
                $imgCropWidth,
                $imgCropHeight,
                $imgCropWidth,
                $imgCropHeight
            );
            imagejpeg($imageCropped, $absoluteSystemPathToNewImage, 90);


            $imageFinal = $imageCropped;
        }




        // Verifica se a extenção do nome da imagem final confere com a original
        // altera em caso de discordancia.
        $nExt = strtolower(strrchr($absoluteSystemPathToNewImage, "."));
        if ($nExt != $ext) {
            $absoluteSystemPathToNewImage .= "_" . $ext;
        }



        // Salva a imagem conforme sua extenção
        switch ($ext) {
            case ".jpg":
            case ".jpeg":
                imagejpeg($imageFinal, $absoluteSystemPathToNewImage, 90);
                break;

            case ".gif":
                imagegif($imageFinal, $absoluteSystemPathToNewImage);
                break;

            case ".png":
                imagepng($imageFinal, $absoluteSystemPathToNewImage, 0);
                break;
        }


        imagedestroy($imageOriginal);
        imagedestroy($imageFinal);


        // Testa se a imagem nova foi gerada com sucesso
        $r = file_exists($absoluteSystemPathToNewImage);
    }



    return $r;
}