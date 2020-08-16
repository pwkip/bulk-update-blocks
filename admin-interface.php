<?php
add_action( 'admin_menu', function () {
	add_menu_page(
		__( 'Bulk Update Blocks', 'my-textdomain' ),
		__( 'Bulk Update Blocks', 'my-textdomain' ),
		'manage_options',
		'bulk-update-blocks',
		'bub_page_contents',
		'dashicons-schedule', 
		90
	);
} );


function bub_page_contents() {

	global $wp;

	$page_id = isset($_REQUEST['sub']) ? $_REQUEST['sub'] : 0;

	$forms = [
		[
			'id' => 'bub_insert_blocks',
			'title' => 'Insert blocks',
			'fields' => [
				[
					'id' => 'bub-blocks',
					'label' => 'Blocks to insert',
					'description' => 'Paste the raw blocks code, including the <code>&lt;!-- wp:block --&gt;</code> tags',
					'type' => 'textarea',
				],
				[
					'id' => 'bub-posts',
					'label' => 'Posts to update',
					'description' => 'Comma separated list of post. Can be a combination of IDs and post types',
					'type' => 'text',
				],
				[
					'id' => 'bub-position',
					'label' => 'Position',
					'description' => 'Where to insert the block. `1` means insert as first block. `-1` means insert as last block. `3` means insert as 3th block.',
					'type' => 'number',
				],
			],
			'submit' => 'Insert Blocks!',
		],
		[
			'id' => 'bub_replace_block',
			'title' => 'Replace block',
			'fields' => [
				[
					'id' => 'bub-block-to-find',
					'label' => 'Block to find',
					'description' => 'Name of block to replace, including namespace. For example <code>core/paragraph</code> or <code>xyz/myblock</code>.<br>Will replace all occurences of this block.',
					'type' => 'text',
				],
				[
					'id' => 'bub-attrs-to-find',
					'label' => 'Attributes to find<br>(optional)',
					'description' => 'Specify extra attributes the found block should have. Must be valid JSON. <code>{"type":"my-type"}</code>',
					'type' => 'textarea',
				],
				[
					'id' => 'bub-blocks-to-replace',
					'label' => 'Replace with',
					'description' => 'Paste the raw blocks code, including the <code>&lt;!-- wp:xyz/block --&gt;</code> tags',
					'type' => 'textarea',
				],
				[
					'id' => 'bub-posts',
					'label' => 'Posts to update',
					'description' => 'Comma separated list of post IDs and/or post types (use the exact slug)',
					'type' => 'textarea',
				],
			],
			'submit' => 'Replace Blocks!',
		],
		[
			'id' => 'bub_delete_block',
			'title' => 'Delete block',
			'fields' => [
				[
					'id' => 'bub-block-to-delete',
					'label' => 'Block to find',
					'description' => 'Name of block to delete, including namespace. For example <code>core/paragraph</code>.<br>Will delete all occurences of this block.',
					'type' => 'text',
                ],
                [
					'id' => 'bub-attrs',
					'label' => 'Attributes<br>(optional)',
					'description' => 'Specify extra attributes the block should have, in order for it to be deleted. Must be valid JSON. <code>{"type":"my-type"}</code>',
					'type' => 'textarea',
				],
				[
					'id' => 'bub-posts',
					'label' => 'Posts to update',
					'description' => 'Comma separated list of post IDs and/or post types (use the exact slug)',
					'type' => 'text',
				],
			],
			'submit' => 'Replace Blocks!',
		],
		[
			'id' => 'bub_find_posts_containing_block',
			'title' => 'Find posts containing block',
			'fields' => [
				[
					'id' => 'bub-block',
					'label' => 'Block to look for',
					'description' => 'Name of block to look for, including namespace. For example <code>core/paragraph</code>',
					'type' => 'text',
                ],
                [
					'id' => 'bub-attrs',
					'label' => 'Attributes<br>(optional)',
					'description' => 'Specify extra attributes the block should have. Must be valid JSON. <code>{"type":"my-type"}</code>',
					'type' => 'textarea',
				],
				[
					'id' => 'bub-posts',
					'label' => 'Posts to include in search<br>(optional)',
					'description' => 'Comma separated list of post IDs and/or post types (use the exact slug)',
					'type' => 'text',
				],
			],
			'submit' => 'Find Posts!',
		],
	];


	?>
		<style>
			textarea, input[type=text] {width: 100%;}
		</style>

		<h1><?php esc_html_e( 'Bulk Update Blocks', 'bulk-update-blocks' ); ?></h1>
		<p>
			<strong>MAKE A BACKUP FIRST!</strong><br>
			This tool is in beta! It's quickly put together, and made it public in the hope to be useful at this very early stage.<br>
			It has no validation and <strong>IT WILL BREAK YOUR WEBSITE</strong> if you don't know what you are doing.
		</p>

		<nav>
			<?php
				echo '| ';
				foreach($forms as $key => $form) {
					$url = site_url().'/wp-admin/admin.php?page=bulk-update-blocks&sub='.$key;
					echo '<a href="'.$url.'">'.$form['title'].'</a> | ';
				}
			?>
		</nav>
		
		<?php
			bub_render_admin_form($forms[$page_id]);
		?>

		<script>
			(function($){
				$('form.bulk-update-form').eq(0).submit(function(e){

					const formData = new FormData(this);
					const data = {};

					for (const [key, value]  of formData.entries()) {
						data[key] = value;
					}

					$('#bub-result').html('Busy. please wait...');

					$.post(ajaxurl, data, function(response){
						$('#bub-result').html(JSON.stringify(response, null, 2));
					},'json');

					e.preventDefault();
					return false;
				});
			})(jQuery);
		</script>

	<?php
}

function bub_render_admin_form($form) {
	$fields = $form['fields'];
	?>
		<h2><?php echo $form['title']; ?></h2>
		<form id="<?php echo $form['id'] ?>" class="bulk-update-form" method="POST">
			<input type="hidden" name="action" value="<?php echo $form['id'] ?>" />
			<table class="form-table" role="presentation">
				<tbody>
					<?php array_walk($fields, 'bub_render_field'); ?>
				</tbody>
			</table>
			<pre id="bub-result"></pre>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $form['submit'] ?>"></p>
		</form>
	<?php
}

function bub_render_field($field) {
	?>
		<tr>
			<th scope="row"><label for="<?php echo $field['id'] ?>"><?php echo $field['label'] ?></label></th>
			<td>
				<?php bub_render_input($field) ?>
				<p class="description" id="tagline-description"><?php echo $field['description'] ?></p>
			</td>
		</tr>
	<?php
}

function bub_render_input($field) {
	$id = $field['id'];
	$type = $field['type'];
	if ($type == 'textarea') {
		echo "<textarea name=\"{$id}\" id=\"{$id}\"></textarea>";
	} else {
		echo "<input type=\"{$type}\" name=\"{$id}\" id=\"{$id}\" />";
	}
}

function bub_get_post_IDs($posts_str) {

	if ($posts_str == '') {
		// get all posts
		$post_ids = get_posts([
			'post_type' => get_post_types(),
			'post_status' => 'publish',
			'numberposts' => -1,
			'fields' => 'ids',
		]);
		return $post_ids;
	}

	$values = explode(',',$posts_str);
	$post_ids = [];
	foreach($values as $val) {
		$val = trim($val);
		if (is_numeric($val)) { 
			$post_ids[] = intval($val);
			continue;
		}
		$posts = get_posts([
			'post_type' => $val,
			'post_status' => 'publish',
			'numberposts' => -1
		]);

		foreach($posts as $post) {
			$post_ids[] = $post->ID;
		}
	}
	return $post_ids;
}

