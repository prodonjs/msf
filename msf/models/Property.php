<?php
namespace msf\models;

class Property {
    const MODEL_NAME = 'property';

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
     * Array of validation errors
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
        if(empty($this->type)) {
            $this->validationErrors['type'] = 'Property type is a required attribute';
        }
        if(empty($this->amountFinanced) || !is_numeric($this->amountFinanced) || $this->amountFinanced < 0) {
            $this->validationErrors['amountFinanced'] = 'Financed amount is a required attribute and must be greater than $0';
        }
        if(empty($this->description)) {
            $this->validationErrors['description'] = 'Industry is a required attribute';
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
        $data = $this->toData();
        return $this->_dataSource->write($data, self::MODEL_NAME);
    } // end save()

    /**
     * Returns an array of all relevant data fields
     * @return array
     */
    public function toData() {
        $data = array();
        foreach($this->_dataFields as $f) {
            $data[$f] = $this->{$f};
        }
        return $data;
    } // end _toData()

    /**
     * Populates this objects data fields from the given array
     * @return void
     */
    public function fromData($data) {
        foreach($this->_dataFields as $f) {
             $this->{$f} = $data[$f];
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
