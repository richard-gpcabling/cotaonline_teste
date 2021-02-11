<?php

class ProdutoDescricao extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=5, nullable=false)
     */
    public $codigo_produto;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $texto;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $anexo;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $imagem;

    /**
     * Valid images
     *
     * @var array
     */
    const VALID_IMAGES = [
        'jpg',
        'png',
        'gif',
        'JPG',
        'PNG',
        'GIF',
        'jpeg',
        'JPEG',
        'bmp',
        'BMP'
    ];

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'produto_descricao';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoDescricao[]|ProdutoDescricao
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProdutoDescricao
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Add or Update
     *
     * @param array $data
     * @param $id_usuario
     * @param $log_content
     * @return int
     */
    public static function saveAdmin($data, $id_usuario, $log_content)
    {
        if (!isset($data['codigo_produto'])) {
            return 0;
        }

        $entity = self::findFirst(['codigo_produto = ' . $data['codigo_produto']]);
        if ($entity) {
            $entity->save($data);
        } else {
            $entity = new ProdutoDescricao();
            $entity->save($data);
        }

        // Log
        self::saveLog($data['codigo_produto'], $id_usuario, $log_content);

        return $entity->id;
    }

    /**
     * Create dir produto_document and produto_imagem
     *
     * @param $codigo_produto
     * @return bool
     */
    public static function createDirByCodigoProduto($codigo_produto)
    {
        $data = ['codigo_produto' => $codigo_produto];

        $dirDocument = APP_PATH . 'public/produto_documento/' . $codigo_produto;
        $data['anexo'] = 'produto_documento/' . $codigo_produto;
        if (!is_dir($dirDocument)) {
            mkdir($dirDocument);
        }

        $dirImagem = APP_PATH . 'public/produto_imagem/' . $codigo_produto;
        $data['imagem'] = 'produto_imagem/' . $codigo_produto;
        if (!is_dir($dirImagem)) {
            mkdir($dirImagem);
        }

        $entity = self::findFirst(['codigo_produto = ' . $data['codigo_produto']]);
        if ($entity) {
            return $entity->save($data);
        } else {
            $entity = new ProdutoDescricao();
            return $entity->save($data);
        }
    }

    /**
     * Save log
     *
     * @param $codigo_produto
     * @param $id_usuario
     * @param $content
     */
    public static function saveLog($codigo_produto, $id_usuario, $content)
    {
        // Log
        $log = new LogProdutoContent();
        $log->user_id = $id_usuario;
        $log->codigo_produto = $codigo_produto;
        $log->content = $content;
        $log->save();
    }

    /**
     * Get main image
     *
     * @param $codigo_produto
     * @return ProdutoDescricao|null
     */
    public static function getMainImageByCodigoProduto($codigo_produto)
    {
        $description = self::findFirst(["codigo_produto = $codigo_produto"]);

        if (!$description) {
            return null;
        }
        $imagem = trim($description->imagem);
        if (empty($imagem) || !is_dir($imagem)) {
            $description->imagem = null;
            return $description;
        }

        $imgData = null; $extension = null;
        foreach(scandir($imagem) as $file){
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if(in_array($extension, self::VALID_IMAGES)){
                $imgData = self::getImageAttributes($imagem . '/' . $file);
                break;
            }
        }

        $description->imagem = $imgData;
        return $description;
    }

    /**
     * Get image attributes
     *
     * @param $image
     *
     * @return \stdClass|null
     */
    public static function getImageAttributes($image)
    {
        // Validation
        if (!file_exists($image)) {
            return null;
        }

        $pathinfo = pathinfo($image);
        $pathinfo['file'] = $image;

        // Calculate image aspect ratio size
        $imageSize = GetImageSize($pathinfo['file']);

        // Verify the greater size
        if($imageSize[0] > $imageSize[1]) {
            $big_side = $imageSize[0];
            $small_side = $imageSize[1];
        } else {
            $big_side = $imageSize[1];
            $small_side = $imageSize[0];
        }

        $i = round($small_side*95/$big_side, 2);
        $i = round((95-$i)/2);

        if($imageSize[0] < $imageSize[1]) {
            $padding = 'style="padding:0 '.$i.'px;"';
        } else {
            $padding = 'style="padding:'.abs($i).'px 0;"';
        }

        $imgData = new stdClass();
        $imgData->name = $pathinfo['filename'];
        $imgData->extension = $pathinfo['extension'];
        $imgData->width = $imageSize[0];
        $imgData->height = $imageSize[1];
        $imgData->relativePath = $pathinfo['dirname'];
        $imgData->file = $image;
        $imgData->relativeUrl = '/' . $image;
        $imgData->htmlPadding = $padding;
        $imgData->htmlAttrSize = $imageSize[3];

        return $imgData;
    }
}
