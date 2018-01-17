<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * LifterLMS Quiz Question
 * @since    1.0.0
 * @version  [version]
 *
 * @property  $question_type  (string)  type of question
 */
class LLMS_Question extends LLMS_Post_Model {

	protected $db_post_type = 'llms_question';
	protected $model_post_type = 'question';

	protected $properties = array(
		'description_enabled' => 'yesno',
		'description' => 'html',
		// 'image' => '',
		'multi_choices' => 'yesno',
		'points' => 'absint',
		'question_type' => 'string',
		'question' => 'html',
		'parent_id' => 'absint',
		'video_enabled' => 'yesno',
		'video_src' => 'string',
	);

	/**
	 * Create a new question choice
	 * @param    array     $data  array of question choice data
	 * @return   string|boolean
	 * @since    [version]
	 * @version  [version]
	 */
	function create_choice( $data ) {

		$data = wp_parse_args( $data, array(
			'choice' => '',
			'choice_type' => 'text',
			'correct' => false,
			'marker' => $this->get_next_choice_marker(),
			'question_id' => $this->get( 'id' ),
		) );

		$choice = new LLMS_Question_Choice( $this->get( 'id' ) );
		if ( $choice->create( $data ) ) {
			return $choice->get( 'id' );
		}

		return false;

	}

	/**
	 * Delete a choice by ID
	 * @param    string     $id  choice ID
	 * @return   boolean
	 * @since    [version]
	 * @version  [version]
	 */
	function delete_choice( $id ) {

		$choice = $this->get_choice( $id );
		if ( ! $choice ) {
			return false;
		}
		return $choice->delete();

	}

	/**
	 * An array of default arguments to pass to $this->create() when creating a new post
	 * @param    array  $args   args of data to be passed to wp_insert_post
	 * @return   array
	 * @since    [version]
	 * @version  [version]
	 */
	protected function get_creation_args( $args = array() ) {

		if ( isset( $args['title'] ) ) {
			$args['post_title'] = $args['title'];
			unset( $args['title'] );
		}
		if ( isset( $args['content'] ) ) {
			$args['post_content'] = $args['content'];
			unset( $args['content'] );
		}

		$meta = isset( $args['meta_input'] ) ? $args['meta_input'] : array();
		foreach ( array_keys( $this->get_properties() ) as $prop ) {

			if ( isset( $args[ $prop ] ) ) {

				$meta[ $this->meta_prefix . $prop ] = $args[ $prop ];
				unset( $args[ $prop ] );

			}

		}
		$args['meta_input'] = wp_parse_args( $meta, $meta );

		if ( isset( $args['order'] ) ) {
			$args['menu_order'] = $args['order'];
			unset( $args['order'] );
		}

		$args = wp_parse_args( $args, array(
			'comment_status' => 'closed',
			'meta_input'     => array(),
			'menu_order'     => 1,
			'ping_status'	 => 'closed',
			'post_author' 	 => get_current_user_id(),
			'post_content'   => '',
			'post_excerpt'   => '',
			'post_status' 	 => 'publish',
			'post_title'     => '',
			'post_type' 	 => $this->get( 'db_post_type' ),
		) );

		return apply_filters( 'llms_' . $this->model_post_type . '_get_creation_args', $args, $this );

	}

	/**
	 * Retrieve a choice by id
	 * @param    string     $id   Choice ID
	 * @return   obj|false
	 * @since    [version]
	 * @version  [version]
	 */
	function get_choice( $id ) {
		$choice = new LLMS_Question_Choice( $this->get( 'id' ), $id );
		if ( $choice->exists() && $this->get( 'id' ) == $choice->get_question_id() ) {
			return $choice;
		}
		return false;
	}

	/**
	 * Retrieve the question's choices
	 * @param    string     $return  return type [choices|ids]
	 * @return   array
	 * @since    [version]
	 * @version  [version]
	 */
	public function get_choices( $return = 'choices' ) {

		$query = wp_cache_get( $this->get_choice_cache_key(), 'llms' );

		if ( false === $query ) {

			global $wpdb;
			$query = $wpdb->get_results( $wpdb->prepare(
				"SELECT meta_key AS id
					  , meta_value AS data
				 FROM {$wpdb->postmeta}
				 WHERE post_id = %d
				   AND meta_key LIKE '_llms_choice_%'
				;", $this->get( 'id' )
			) );

			usort( $query, function( $a, $b ) {
				$adata = unserialize( $a->data );
				$bdata = unserialize( $b->data );
				return strcmp( $adata['marker'], $bdata['marker'] );
			} );

			wp_cache_set( $this->get_choice_cache_key(), $query, 'llms' );

		}

		if ( 'ids' === $return ) {
			return wp_list_pluck( $query, 'id' );
		}

		$ret = array();
		foreach ( $query as $result ) {
			$ret[] = new LLMS_Question_Choice( $this->get( 'id' ), unserialize( $result->data ) );
		}

		return $ret;

	}

	/**
	 * Retrieve child questions (for question group)
	 * @todo     need to prevent access for non-group questions...
	 * @return   array
	 * @since    [version]
	 * @version  [version]
	 */
	public function get_questions() {
		return $this->questions()->get_questions();
	}

	/**
	 * Retrieves an object cache key for the question's choices
	 * @return   string
	 * @since    [version]
	 * @version  [version]
	 */
	private function get_choice_cache_key() {

		return sprintf( 'question_%d_choices', $this->get( 'id' ) );

	}

	/**
	 * Retrieve the next marker for question choices
	 * @return   string
	 * @since    [version]
	 * @version  [version]
	 */
	protected function get_next_choice_marker() {
		$next_index = count( $this->get_choices( 'ids', false ) ) + 1;
		$markers = llms_get_question_choice_markers();
		return $next_index > count( $markers ) ? false : $markers[ $next_index ];
	}

	/**
	 * Retrieve question type data for the given question
	 * @return   array
	 * @since    [version]
	 * @version  [version]
	 */
	public function get_question_type() {
		return  llms_get_question_type( $this->get( 'question_type' ) );
	}

	/**
	 * Retrieve an instance of the questions parent LLMS_Quiz
	 * @return   obj
	 * @since    [version]
	 * @version  [version]
	 */
	public function get_quiz() {
		return new LLMS_Quiz( $this->get( 'quiz_id' ) );
	}

	/**
	 * Access question manager (used for question groups)
	 * @todo     need to prevent access for non-group questions...
	 * @return   obj
	 * @since    [version]
	 * @version  [version]
	 */
	public function questions() {
		return new LLMS_Question_Manager( $this );
	}

	/**
	 * Determine if the question supports a question feature
	 * @param    string     $feature  name of the feature (eg "choices")
	 * @return   boolean
	 * @since    [version]
	 * @version  [version]
	 */
	public function supports( $feature ) {

		$ret = false;

		$type = $this->get_question_type();
		if ( $type ) {
			if ( 'choices' === $feature ) {
				$ret = ( ! empty( $type['choices'] ) );
			} elseif ( 'points' === $feature ) {
				$ret = $type['points'];
			}
		}

		/**
		 * @filter   llms_{$question_type}_question_supports
		 * @param    boolean   $ret      return value
		 * @param    string    $string   name of the feature being checked (eg "choices")
		 * @param    obj       $this     instance of the LLMS_Question
		 * @usage    apply_filters( 'llms_choice_question_supports', function( $ret, $feature, $question ) {
		 *           	return $ret;
		 *           }, 10, 3 );
		 */
		return apply_filter( 'llms_' . $this->get( 'question_type' ) . '_question_supports', $ret, $feature, $this );

	}

	/**
	 * Called before data is sorted and returned by $this->toArray()
	 * Extending classes should override this data if custom data should
	 * be added when object is converted to an array or json
	 * @param    array     $arr   array of data to be serialized
	 * @return   array
	 * @since    3.3.0
	 * @version  [version]
	 */
	protected function toArrayAfter( $arr ) {

		unset( $arr['author'] );
		unset( $arr['date'] );
		unset( $arr['excerpt'] );
		unset( $arr['menu_order'] );
		unset( $arr['modified'] );
		unset( $arr['status'] );

		// $arr['options'] = $this->get_options();
		$choices = array();
		foreach ( $this->get_choices() as $choice ) {
			$choices[] = $choice->get_data();
		}
		$arr['choices'] = $choices;

		if ( 'group' === $this->get( 'question_type' ) ) {
			$arr['questions'] = array();
			foreach ( $this->get_questions() as $question ) {
				$arr['questions'][] = $question->toArray();
			}
		}

		return $arr;

	}

	/**
	 * Update a question choice
	 * if no id is supplied will create a new choice
	 * @param    array     $data  array of choice data (see $this->create_choice())
	 * @return   string|boolean
	 * @since    [version]
	 * @version  [version]
	 */
	function update_choice( $data ) {

		// if there's no ID, we'll add a new choice
		if ( ! isset( $data['id'] ) ) {
			return $this->create_choice( $data );
		}

		// get the question
		$choice = $this->get_choice( $data['id'] );
		if ( ! $choice ) {
			return false;
		}

		$choice->update( $data )->save();

		// return choice ID
		return $choice->get( 'id' );


	}





















	/**
	 * Get the correct option for the question
	 * @return   array|null
	 * @since    1.0.0
	 * @version  3.9.0
	 */
	public function get_correct_option() {
		$options = $this->get_options();
		$key = $this->get_correct_option_key();
		if ( ! is_null( $key ) && isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}
		return null;
	}

	/**
	 * Get the key of the correct option
	 * @return   int|null
	 * @since    3.9.0
	 * @version  3.9.0
	 */
	public function get_correct_option_key() {
		$options = $this->get_options();
		foreach ( $options as $key => $option ) {
			if ( $option['correct_option'] ) {
				return $key;
			}
		}
		return null;
	}

	/**
	 * Retrieve quizzes this quiz is assigned to
	 * @return   array              array of WP_Post IDs (quiz post types)
	 * @since    3.12.0
	 * @version  3.12.0
	 */
	public function get_quizzes() {

		$id = absint( $this->get( 'id' ) );
		$len = strlen( strval( $id ) );

		$str_like = '%' . sprintf( 's:2:"id";s:%1$d:"%2$s";', $len, $id ) . '%';
		$int_like = '%' . sprintf( 's:2:"id";i:%1$s;', $id ) . '%';

		global $wpdb;
		$query = $wpdb->get_col(
			"SELECT post_id
			 FROM {$wpdb->postmeta}
			 WHERE meta_key = '_llms_questions'
			   AND (
			   	      meta_value LIKE '{$str_like}'
			   	   OR meta_value LIKE '{$int_like}'
			   );"
		);

		return $query;

	}

	/*
		       /$$                                                               /$$                     /$$
		      | $$                                                              | $$                    | $$
		  /$$$$$$$  /$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$$  /$$$$$$  /$$$$$$    /$$$$$$   /$$$$$$$
		 /$$__  $$ /$$__  $$ /$$__  $$ /$$__  $$ /$$__  $$ /$$_____/ |____  $$|_  $$_/   /$$__  $$ /$$__  $$
		| $$  | $$| $$$$$$$$| $$  \ $$| $$  \__/| $$$$$$$$| $$        /$$$$$$$  | $$    | $$$$$$$$| $$  | $$
		| $$  | $$| $$_____/| $$  | $$| $$      | $$_____/| $$       /$$__  $$  | $$ /$$| $$_____/| $$  | $$
		|  $$$$$$$|  $$$$$$$| $$$$$$$/| $$      |  $$$$$$$|  $$$$$$$|  $$$$$$$  |  $$$$/|  $$$$$$$|  $$$$$$$
		 \_______/ \_______/| $$____/ |__/       \_______/ \_______/ \_______/   \___/   \_______/ \_______/
		                    | $$
		                    | $$
		                    |__/
	*/

	/**
	 * Get the options for the question
	 * @return     array
	 * @since      1.0.0
	 * @version    [version]
	 * @deprecated [version]
	 */
	public function get_options() {

		llms_deprecated_function( 'LLMS_Question::get_options()', '3.16.0', 'LLMS_Question::get_choices()' );
		return $this->get_choices();

	}


}
