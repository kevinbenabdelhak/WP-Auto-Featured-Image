<?php 
if (!defined('ABSPATH')) {
    exit;
}

function my_auto_featured_image_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $post_types = get_post_types(array('public' => true), 'objects');

    ?>
    <div class="wrap">
        <h1>WP Auto Featured Image</h1>
        <p>Cliquez sur un bouton pour mettre à jour les images mises en avant pour le type de contenu choisi.</p>
        
        <div id="buttons-container">
            <?php foreach ($post_types as $post_type) : 
                if ($post_type->name == 'attachment') continue;
                ?>
                <button class="update-button button button-primary" data-post-type="<?php echo esc_attr($post_type->name); ?>">
                    Mettre à jour tous les <?php echo esc_html($post_type->labels->name); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="progress-container" style="display: none; margin-top: 20px;">
            <p>Traitement en cours : <span id="processed-count">0</span> sur <span id="total-count">0</span> articles.</p>
            <div style="background: #ccc; border-radius: 5px; overflow: hidden; width: 100%; height: 25px;">
                <div id="progress-bar" style="background: #0073aa; width: 0%; height: 100%; transition: width 0.3s;"></div>
            </div>
        </div>
        <p id="result-message"></p>
    </div>
    <script>
   
    jQuery(document).ready(function($) {
        var buttons = $('.update-button');
        var progressContainer = $('#progress-container');
        var processedCountSpan = $('#processed-count');
        var totalCountSpan = $('#total-count');
        var progressBar = $('#progress-bar');
        var resultMessage = $('#result-message');

        var postIds = [];
        var totalPosts = 0;
        var processedPosts = 0;
        var successCount = 0;
        var nonce = '<?php echo wp_create_nonce('my-auto-featured-image-nonce'); ?>';

        buttons.on('click', function() {
            var postType = $(this).data('post-type');
            var buttonText = $(this).text();

            if (confirm('Êtes-vous sûr de vouloir mettre à jour les images pour ce type de contenu ?')) {
                buttons.prop('disabled', true);
                $(this).text('Traitement en cours...');
                resultMessage.text('');
                progressContainer.show();
                processedPosts = 0;
                successCount = 0;

                $.post(
                    '<?php echo admin_url('admin-ajax.php'); ?>', {
                        'action': 'run_my_auto_featured_image',
                        'nonce': nonce,
                        'post_type': postType
                    },
                    function(response) {
                        if (response.success) {
                            postIds = response.data.ids;
                            totalPosts = postIds.length;
                            totalCountSpan.text(totalPosts);
                            processNextPost();
                        } else {
                            resultMessage.text('Erreur lors de la récupération des articles.');
                            buttons.prop('disabled', false);
                            buttons.filter('[data-post-type="' + postType + '"]').text(buttonText);
                        }
                    }
                );
            }
        });

        function processNextPost() {
            if (postIds.length > 0) {
                var postId = postIds.shift();
                
                $.post(
                    '<?php echo admin_url('admin-ajax.php'); ?>', {
                        'action': 'process_my_auto_featured_image',
                        'nonce': nonce,
                        'post_id': postId
                    },
                    function(response) {
                        if (response.success && response.data.updated) {
                            successCount++;
                        }
                        processedPosts++;
                        updateProgress();
                        processNextPost();
                    }
                ).fail(function() {
                    processedPosts++;
                    updateProgress();
                    processNextPost();
                });
            } else {
                buttons.prop('disabled', false);
                resultMessage.text('Processus terminé ! ' + successCount + ' images mises à jour avec succès.');
                alert('Mise à jour terminée !');
            }
        }

        function updateProgress() {
            processedCountSpan.text(processedPosts);
            var progress = (processedPosts / totalPosts) * 100;
            progressBar.css('width', progress + '%');
        }
    });
    </script>
    <?php
}