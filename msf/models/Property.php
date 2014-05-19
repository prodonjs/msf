<?php
namespace msf\models;

class Property {
    /**
     * Model name used by data source
     */
    const MODEL_NAME = 'property';

    /**
     * Preferred dimensions for property Image
     */
    const PREFERRED_IMG_WIDTH = 600;
    const PREFERRED_IMG_HEIGHT = 320;
    const THUMBNAIL_WIDTH = 300;
    const THUMBNAIL_HEIGHT = 160;

    /**
     * Valid type values
     */
    public static $validTypes = array(
        'Multi-Family',
        'Retail',
        'Office',
        'Industry'
    );

    /**
     * Identifer
     * @var string
     */
	public $id;

    /**
     * Name of property
     * @var string
     */
    public $name;

    /**
     * City of property
     * @var string
     */
    public $city;

    /**
     * State of property
     * @var string
     */
    public $state;

    /**
     * Type of property
     * @var string
     */
    public $type;

    /**
     * Financed amount of property
     * @var float
     */
    public $amountFinanced;

    /**
     * Description of property
     * @var string
     */
    public $description;

    /**
     * Closing date of financing
     * @var string
     */
    public $closingDate;

    /**
     * Image associated with property
     * @var msf\models\Image
     */
    public $image;

    /**
     * Created date
     * @var string
     */
    public $created;

    /**
     * Last modified date
     * @var string
     */
    public $modified;

    /**
     * Array of validation errors keyed by fieldname
     *
     * @var array
     */
    public $validationErrors;

    /**
     * FileDataSource to handle persistence
     * @var FileDataSource
     */
    private $_dataSource;

    /**
     * Array of object fieldnames holding data
     * @var array
     */
    private $_dataFields = array(
        'id',
        'name',
        'city',
        'state',
        'type',
        'amountFinanced',
        'description',
        'closingDate',
        'image',
        'created',
        'modified'
    );

    public function __construct(FileDataSource $dataSource) {
        $this->_dataSource = $dataSource;
    } // end __construct

    public function validate() {
        $this->validationErrors = array();
        if(empty($this->name)) {
            $this->validationErrors['name'] = 'Name is a required attribute';
        }
        if(empty($this->city)) {
            $this->validationErrors['city'] = 'City is a required attribute';
        }
        if(empty($this->state) || strlen($this->state) !== 2) {
            $this->validationErrors['state'] = 'State is a required attribute and must be exactly 2 characters';
        }
        if(empty($this->type) || !in_array($this->type, self::$validTypes)) {
            $this->validationErrors['type'] = 'Property type is a required attribute and must be one of ' . implode(', ', self::$validTypes);
        }
        if(empty($this->amountFinanced) || !is_numeric($this->amountFinanced) || $this->amountFinanced < 0) {
            $this->validationErrors['amountFinanced'] = 'Financed amount is a required attribute and must be greater than $0';
        }
        if(empty($this->description)) {
            $this->validationErrors['description'] = 'Industry is a required attribute';
        }
        if(empty($this->closingDate) || !strtotime($this->closingDate)) {
            $this->validationErrors['closingDate'] = 'Closing date must be a valid date';
        }
        return empty($this->validationErrors);
    } // end validate()

    /**
     * Persists this model's data to the underlying data source.
     * @return string
     */
    public function save() {
        $this->modified = date('Y-m-d H:i:s');
        if(empty($this->id)) {
            $this->id = md5("{$this->name}{$this->type}{$this->description}");
            $this->created = $this->modified;
        }
        if(!$this->validate()) {
            return false;
        }
        $data = $this->toData();
        return $this->_dataSource->write($data, self::MODEL_NAME);
    } // end save()

    /**
     * Delete this Property from the data source
     * @return boolean
     */
    public function delete() {
        if($this->id) {
            if($this->image instanceof \msf\models\Image) {
                unlink($this->image->fullPath);
                unlink($this->image->thumbnailPath);
            }
            return $this->_dataSource->delete(self::MODEL_NAME, $this->id);
        }
        return false;
    }

    /**
     * Transforms this object's data members into an associative
     * array
     * @return array
     */
    public function toData() {
        $data = array();
        foreach($this->_dataFields as $f) {
            if(!$this->{$f}) {
                continue;
            }
            if($f === 'image' && $this->{$f} instanceof \msf\models\Image) {
                $data[$f] = $this->image->name;
            }
            else {
                $data[$f] = $this->{$f};
            }
        }
        return $data;
    } // end _toData()

    /**
     * Populates this objects data fields from the given array
     * @return void
     */
    public function fromData($data) {
        foreach($data as $f => $value) {
            if($f !== 'image') {
                $this->{$f} = $value;
            }
            else {
                /* Instantiate the Image using its full path */
                $imagePath = $this->_dataSource->getSubTypePath(FileDataSource::IMAGES) . DIRECTORY_SEPARATOR;
                $this->image = new \msf\models\Image($imagePath . $value);
            }
        }
    } // end _toData()

    /**
     * Uses the supplied identifer and data source and tries
     * to read and instantiate a Property object
     * @param string|int $id
     * @param FileDataSource $dataSource
     */
    public static function Get($id, FileDataSource $dataSource) {
        $data = $dataSource->read(self::MODEL_NAME, $id);
        $property = new Property($dataSource);
        $property->fromData($data);

        return $property;
    } // end Get()

    /**
     * Uses the supplied identifer and data source and tries
     * to read and instantiate a Property object
     * @param string|int $id
     * @param FileDataSource $dataSource
     */
    public static function FindAll(FileDataSource $dataSource, $limit=0, $orderBy='', $direction='ASC') {
        $data = $dataSource->readAll(self::MODEL_NAME, $limit, $orderBy, $direction);
        $properties = array();
        foreach($data as $d) {
            $p = new Property($dataSource);
            $p->fromData($d);
            $properties[] = $p;
        }
        return $properties;
    } // end Get()
} // end Property
