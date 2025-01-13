jQuery(document).ready(function ($) {
    $('.icp-vote-button').on('click', function () {
        const button = $(this);
        const postId = button.data('post-id');
        const card = button.closest('.icp-card'); // Find the current card

        $.ajax({
            url: icp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'icp_vote',
                nonce: icp_ajax.nonce,
                post_id: postId,
            },
            success: function (response) {
                if (response.success) {
                    // Update votes for the current card
                    card.find('.icp-vote-count').text(response.data.votes);

                    // Update total votes for all cards
                    $('.icp-total-votes').text(response.data.total_votes);
                } else {
                    alert(response.data.message || 'Something went wrong!');
                }
            },
            error: function () {
                alert('Failed to submit vote.');
            },
        });
    });
});
