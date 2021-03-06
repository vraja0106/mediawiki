<?php

/**
 * Basic infrastructure of the field definition.
 * Specific engines will need to override it at least for getMapping,
 * but can reuse other parts.
 * @since 1.28
 */
abstract class SearchIndexFieldDefinition implements SearchIndexField {
	/**
	 * Name of the field
	 *
	 * @var string
	 */
	protected $name;
	/**
	 * Type of the field, one of the constants above
	 *
	 * @var int
	 */
	protected $type;
	/**
	 * Bit flags for the field.
	 *
	 * @var int
	 */
	protected $flags = 0;
	/**
	 * Subfields
	 * @var SearchIndexFieldDefinition[]
	 */
	protected $subfields = [];

	/**
	 * SearchIndexFieldDefinition constructor.
	 * @param string $name Field name
	 * @param int    $type Index type
	 */
	public function __construct( $name, $type ) {
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * Get field name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get index type
	 * @return int
	 */
	public function getIndexType() {
		return $this->type;
	}

	/**
	 * Set global flag for this field.
	 *
	 * @param int  $flag Bit flag to set/unset
	 * @param bool $unset True if flag should be unset, false by default
	 * @return $this
	 */
	public function setFlag( $flag, $unset = false ) {
		if ( $unset ) {
			$this->flags &= ~$flag;
		} else {
			$this->flags |= $flag;
		}
		return $this;
	}

	/**
	 * Check if flag is set.
	 * @param $flag
	 * @return int 0 if unset, !=0 if set
	 */
	public function checkFlag( $flag ) {
		return $this->flags & $flag;
	}

	/**
	 * Merge two field definitions if possible.
	 *
	 * @param SearchIndexField $that
	 * @return SearchIndexField|false New definition or false if not mergeable.
	 */
	public function merge( SearchIndexField $that ) {
		// TODO: which definitions may be compatible?
		if ( ( $that instanceof self ) && $this->type === $that->type &&
		     $this->flags === $that->flags && $this->type !== self::INDEX_TYPE_NESTED
		) {
			return $that;
		}
		return false;
	}

	/**
	 * Get subfields
	 * @return SearchIndexFieldDefinition[]
	 */
	public function getSubfields() {
		return $this->subfields;
	}

	/**
	 * Set subfields
	 * @param SearchIndexFieldDefinition[] $subfields
	 * @return $this
	 */
	public function setSubfields( array $subfields ) {
		$this->subfields = $subfields;
		return $this;
	}
}
