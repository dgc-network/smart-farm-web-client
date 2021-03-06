<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: schema_state.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>SchemaList</code>
 */
class SchemaList extends \Google\Protobuf\Internal\Message
{
    /**
     * Schemas are stored in a list to handle any hash collisions
     *
     * Generated from protobuf field <code>repeated .Schema schemas = 1;</code>
     */
    private $schemas;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Schema[]|\Google\Protobuf\Internal\RepeatedField $schemas
     *           Schemas are stored in a list to handle any hash collisions
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\SchemaState::initOnce();
        parent::__construct($data);
    }

    /**
     * Schemas are stored in a list to handle any hash collisions
     *
     * Generated from protobuf field <code>repeated .Schema schemas = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * Schemas are stored in a list to handle any hash collisions
     *
     * Generated from protobuf field <code>repeated .Schema schemas = 1;</code>
     * @param \Schema[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSchemas($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Schema::class);
        $this->schemas = $arr;

        return $this;
    }

}

