<?php
/**
 * Plugin Name: Kommentar Lizenzerweiterung
 * Description: Erweitert die Kommentarfunktion um ein Feld zur Auswahl, unter welcher Lizenz der Kommentar veröffentlicht werden soll.
 * Author: nova GmbH // J&K – Jöran und Konsorten GmbH & Co. KG // OERcamp
 * Version: 1.0.0
 * License: MIT License
 * License URL: https://opensource.org/licenses/MIT
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 *
 * The above copyright notice and this permission notice shall be included in al
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS O
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL TH
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHE
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN TH
 * SOFTWARE.
 */

wp_enqueue_style( 'nova-comment-licences-style', plugin_dir_url( __FILE__ ) . '/style.css' );

add_filter( 'get_comment_date', 'add_licence', 10, 3 );

function add_licence( $date, $d, $comment ) {

	if ( get_field( 'lizenz', $comment ) ) {

		$licence_val   = get_field( 'lizenz', $comment )['value'];
		$licence_label = get_field( 'lizenz', $comment )['label'];

		if ( $licence_val != 'none' ) :

			$licence_url = 'https://creativecommons.org/licenses/';

			switch ( $licence_val ) {
				case 'ccby':
					$p = 'by/4.0';
					break;
				case 'ccbysa':
					$p = 'by-sa/4.0';
					break;
				case 'ccbynd':
					$p = 'by-nd/4.0';
					break;
				case 'ccbync':
					$p = 'by-nc/4.0';
					break;
				case 'ccbyncsa':
					$p = 'by-nc-sa/4.0';
					break;
				case 'ccbyncnd':
					$p = 'by-nc-nd/4.0';
					break;
				case 'cc0':
					$p = 'zero/1.0';
					break;
			}

			$licence_url .= $p . '/deed.de';

			$licence_content  = '<br><p class="licencetext">';
			$licence_content .= '<a rel="license" target="_blank" href="' . $licence_url . '">';
			$licence_content .= '<img alt="Creative Commons Lizenzvertrag"" class="licenceimg" src="' . plugin_dir_url( __FILE__ ) . 'img/' . $licence_val . '.png">';
			$licence_content .= '</a>';
			$licence_content .= 'Dieser Kommentar ';
			if ( $comment->comment_author ) {
				$licence_content .= 'von ';
				if ( $comment->comment_author_url ) {
					$licence_content .= '<a target="_blank" href="' . $comment->comment_author_url . '">';
				}
				$licence_content .= $comment->comment_author;

				if ( $comment->comment_author_url ) {
					$licence_content .= '</a>';
				}
			}
			$licence_content .= ' steht unter der Lizenz ';
			$licence_content .= '<a rel="license" target="_blank" href="' . $licence_url . '">';
			$licence_content .= $licence_label;
			$licence_content .= '</a>';

			$licence_content .= '. (<a href="https://www.oercamp.de/kommentare-unter-freien-lizenzen/">Hintergrund</a>)</p>';

	  else :
			$licence_content  = '<br><p class="licencetext">';
			$licence_content .= 'Dieser Kommentar ';

			if ( $comment->comment_author ) {
				$licence_content .= 'von ';
				if ( $comment->comment_author_url ) {
					$licence_content .= '<a target="_blank" href="' . $comment->comment_author_url . '">';
				}
				$licence_content .= $comment->comment_author;

				if ( $comment->comment_author_url ) {
					$licence_content .= '</a>';
				}
			}

			$licence_content .= ' steht nicht unter freier Lizenz. (<a href="https://www.oercamp.de/kommentare-unter-freien-lizenzen/">Hintergrund</a>)';

	  endif;

		$comment->comment_content = $comment->comment_content . $licence_content;
	}

}

//register field group
if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_5caf43d0392fe',
			'title'                 => 'Kommentar-Lizenz',
			'fields'                => array(
				array(
					'key'               => 'field_5caf43e6759d6',
					'label'             => 'Lizenz',
					'name'              => 'lizenz',
					'type'              => 'select',
					'instructions'      => 'Bitte wählen Sie, unter welcher Lizenz Ihr Kommentar veröffentlicht werden soll. (<a href="https://www.oercamp.de/kommentare-unter-freien-lizenzen/">Hintergrund</a>)',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'ccby'     => 'CC BY 4.0',
						'ccbysa'   => 'CC BY-SA 4.0',
						'ccbynd'   => 'CC BY-ND 4.0',
						'ccbync'   => 'CC BY-NC 4.0',
						'ccbyncsa' => 'CC BY-NC-SA 4.0',
						'ccbyncnd' => 'CC BY-NC-ND 4.0',
						'cc0'      => 'CC0 (CC Zero)',
						'none'     => ' nicht unter freier Lizenz',
					),
					'default_value'     => array(),
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'array',
					'ajax'              => 0,
					'placeholder'       => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'comment',
						'operator' => '==',
						'value'    => 'all',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);

  endif;
