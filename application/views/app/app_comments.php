<div class="applicationText">
    <h2><?php echo $app_name; ?> - Comments</h2>
    <br clear="all">
</div>
<ol class="commentlist">
    <?php
    foreach ($app_comments as $comment) {
        ?>
        <li class="comment even thread-even depth-1">
            <article class="comment-body clearfix" id="comment-21891">
                <div class="comment-content">
                    <div class="comment-meta">
                        <cite class="fn"><?php echo $comment['first_name'] . ' ' . $comment['last_name']; ?></cite>					
                        <time datetime="<?php echo $comment['created_datetime']; ?>">on <?php echo date('F d, Y H:i:s', strtotime($comment['created_datetime'])); ?>
                        </time>
                    </div>
                    <section class="comment-text clearfix">
                        <p><?php echo $comment['comments']; ?></p>
                    </section>
                </div>
            </article>
        </li>

    <?php } ?>
</ol>

<style>

    ol.commentlist {
        list-style: none outside none !important;
        margin: 0 !important;
    }

    .clearfix:before, .clearfix:after {
        content: " ";
        display: block;
        height: 0;
        overflow: hidden;
        visibility: hidden;
        width: 0;
    }

    .comment-content {
        display: block;

        position: relative;
    }

    .comment-meta {
        display: block;
    }

    .comment-body .fn {
        display: block;
        font-family: 'Raleway',sans-serif;
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        padding: 0 0 15px;
    }
    .comment-body {
        border-bottom: 1px solid #DFDFDF;

        overflow: hidden;

        position: relative;
    }

    .block.loop-single .comment-body .fn a {
        color: #444444;
    }

    .comment-body time {
        color: #D3D3D3;
        display: inline-block;
        font-size: 13px;
        font-weight: bold;
        line-height: 21px;
        position: absolute;
        right: 0;
        text-transform: uppercase;
        top: 0;
    }
    .comment-body time a:hover {
        color: #D3D3D3;
    }
    .comment-body time a {
        color: #D3D3D3;
        font-style: italic;
        font-weight: bold;
        text-decoration: none;
    }

    .block.loop-single .comment-body time a, .block.loop-single .comment-body time a:hover {
        color: #D3D3D3;
    }


    .comment p {
        color: #474646;
        font-size: 14px;
        line-height: 24px;
        margin: 0 0 12px;
    }

</style>