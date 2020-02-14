<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

$wps_tax = get_taxonomy( $object->taxonomy );
?>
<h2><?php echo esc_html( $wps_tax->labels->singular_name ) . ' ' . esc_html__( 'Archive Settings', 'wps' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr class="form-field">
			<th scope="row"><label for="<?php $this->field_id( 'headline' ); ?>"><?php esc_html_e( 'Archive Headline', 'wps' ); ?></label></th>
			<td>
				<input name="<?php $this->field_name( 'headline' ); ?>" id="<?php $this->field_id( 'headline' ); ?>" type="text" value="<?php echo esc_attr( get_term_meta( $object->term_id, 'headline', true ) ); ?>" size="40" />
				<p class="description">
					<?php
					if ( genesis_a11y( 'headings' ) ) {
						esc_html_e( 'Your child theme uses accessible headings. If you leave this blank, the default accessible heading will be used.', 'wps' );
					} else {
						esc_html_e( 'Leave empty if you do not want to display a headline.', 'wps' );
					}
					?>
				</p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="<?php echo $this->settings_field . '-intro-text'; ?>"><?php esc_html_e( 'Archive Intro Text', 'wps' ); ?></label></th>
			<td>
				<?php
				wp_editor(
					get_term_meta( $object->term_id, 'intro_text', true ),
					$this->settings_field . '-intro-text',
					array(
						'textarea_name' => $this->get_field_name( 'intro_text' ),
					)
				);
				?>
				<p class="description"><?php esc_html_e( 'Leave empty if you do not want to display any intro text.', 'wps' ); ?></p>
			</td>
		</tr>
        <tr class="form-field">
            <th scope="row"><label for="<?php $this->field_id( 'headline_image' ); ?>"><?php esc_html_e( 'Archive Headline Image', 'wps' ); ?></label></th>
            <td>
                <?php
                $image_id = get_term_meta( $object->term_id, 'headline_image_id', true );
                ?>
                <input type="hidden" name="<?php $this->field_name( 'headline_image' ); ?>" id="<?php $this->field_id( 'headline_image' ); ?>" value="<?php echo esc_attr( get_term_meta( $object->term_id, 'headline_image', true ) ); ?>" />
                <input type="hidden" name="<?php $this->field_name( 'headline_image_id' ); ?>" id="<?php $this->field_id( 'headline_image_id' ); ?>" value="<?php echo esc_attr( $image_id ); ?>" />
                <button class="button setting-upload"><?php _e('Select/Upload', 'wps' ) ?></button>
                <div class="preview">
		            <?php
		            if ( $image_id ) {
			            echo wp_get_attachment_image( $image_id );
		            } else {
			            echo '<img style="display:none;" class="attachment-thumbnail size-thumbnail" alt="" width="150" height="150">';
		            }
		            ?>
                </div>
                <p class="description">
		            <?php
		            esc_html_e( 'Optional. Upload an image', 'wps' );
		            ?>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="<?php $this->field_id( 'archive_image_size' ); ?>"><b><?php esc_html_e( 'Archive Image Size', 'wps' ); ?></b></label></th>
            <td>
                <p>
                    <select id="<?php echo esc_attr( $this->get_field_id( 'archive_image_size' ) ); ?>" class="genesis-image-size-selector" name="<?php echo esc_attr( $this->get_field_name( 'archive_image_size' ) ); ?>">
						<?php
						printf( '<option value="" %s>%s</option>', selected( '', $this->get_field_value( 'archive_image_size' ), false ), __( 'None', 'wps' ) );
						$sizes = genesis_get_image_sizes();
						foreach ( (array) $sizes as $name => $size ) {
							printf( '<option value="%s" %s>%s (%sx%s)</option>', esc_attr( $name ), selected( $name, $this->get_field_value( 'archive_image_size' ), false ), esc_html( $name ), esc_html( $size['width'] ), esc_html( $size['height'] ) );
						}
						?>
                    </select>
                </p>
                <p class="description">
					<?php
					esc_html_e( 'If empty, archive will use the default global featured image size.', 'wps' );
					?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="<?php $this->field_id( 'archive_image_alignment' ); ?>"><b><?php esc_html_e( 'Archive Image Alignment', 'wps' ); ?></b></label></th>
            <td>
                <p>
                    <select id="<?php echo esc_attr( $this->get_field_id( 'archive_image_alignment' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'archive_image_alignment' ) ); ?>">
                        <option value="alignnone">- <?php esc_html_e( 'None', 'wps' ); ?> -</option>
                        <option value="alignleft" <?php selected( 'alignleft', $this->get_field_value( 'archive_image_alignment' ) ); ?>><?php esc_html_e( 'Left', 'wps' ); ?></option>
                        <option value="alignright" <?php selected( 'alignright', $this->get_field_value( 'archive_image_alignment' ) ); ?>><?php esc_html_e( 'Right', 'wps' ); ?></option>
                        <option value="aligncenter" <?php selected( 'aligncenter', $this->get_field_value( 'archive_image_alignment' ) ); ?>><?php esc_html_e( 'Center', 'wps' ); ?></option>
                    </select>
                </p>
                <p class="description">
					<?php
					esc_html_e( 'If empty, archive will use the default global featured image size.', 'wps' );
					?>
                </p>
            </td>
        </tr>

	</tbody>
</table>

<script>
    (function($) {
        $(document).ready(function($){

            var customUploader,
                $clickElem = $(".setting-upload"),
                $targetImage = $('.wrap input[name="<?php $this->field_name( 'headline_image' ); ?>"]'),
                $previewDiv = $(".preview"),
                $targetImageID = $('.wrap input[name="<?php $this->field_name( 'headline_image_id' ); ?>"]');

            $clickElem.click(function(e) {
                e.preventDefault();
                //If the uploader object has already been created, reopen the dialog
                if (customUploader) {
                    customUploader.open();
                    return;
                }
                //Extend the wp.media object
                customUploader = wp.media.frames.file_frame = wp.media({
                    title: "<?php _e( 'Choose Image', 'wps' ); ?>",
                    button: {
                        text: "<?php _e( 'Choose Image', 'wps' ); ?>"
                    },
                    multiple: false
                });
                //When a file is selected, grab the URL and set it as the text field's value
                customUploader.on('select', function() {
                    debugger;
                    var attachment = customUploader.state().get('selection').first().toJSON();

                    $targetImage.val(attachment.url);
                    $targetImageID.val(attachment.id);

                    $previewDiv.find("img").remove();
                    $previewDiv.html('<img src="'+attachment.sizes.thumbnail.url+'" height="' + attachment.sizes.thumbnail.height + '" width="' + attachment.sizes.thumbnail.width + '">')

                });
                //Open the uploader dialog
                customUploader.open();
            });
        });
    })(jQuery);
</script>
