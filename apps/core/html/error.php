<style>
    .native-error, .native-error * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .native-error {
        margin: 20px;
        border: 1px solid rgba(0, 0, 0, .1);
        border-radius: 10px;
        padding: 0 20px;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .1);
        background-color: white;
    }

    .native-error h2 {
        margin-left: -20px;
        margin-right: -20px;
        background-color: #ddd;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        font-size: 20px;
        font-weight: bold;
        position: relative;
    }

    .native-error h2 span {
        display: inline-block;
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 12px;
        color: rgba(0, 0, 0, .4);
        
    }

    .native-error h2, .native-error p {
        margin-bottom: 0;
        padding: 15px 20px;
        border-bottom: 1px solid rgba(0, 0, 0, .2);
        color: black;
    }

    .native-error-view pre {
        padding: 20px 0;
        font-weight: bold;
        color: rgba(0, 0, 0, .5);
    }

    .native-error-line {
        display: inline;
        color: black;
    }

    .native-error-line:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 20, 20, .3);
    }

    .native-error-linenumber {
        display: inline-block;
        min-width: 30px;
        border-right: 1px solid rgba(255, 20, 20, .3);
        margin-right: 20px;
        padding-left: 10px;
    }

    .native-line {
        position: relative;
        min-width: 100%;
        display: inline-block;
    }
</style>

<div class="native-error">
    <code>
        <h2>An error has occurred <span>ErrorHandler</span></h2>
        <p><b>Message:</b> <?=$message?></p>
        <p><b>Path:</b> <?=$file?>:<b><?=$line?></b></p>
    </code>
    <?if(is_file($file)):?>
        <code class="native-error-view">
            <?php
                $output = explode(PHP_EOL, htmlentities(file_get_contents($file)));

                for($i = 1; $i < 4; $i++) {
                    if(isset($output[$line-$i]))
                        $output[$line-$i] = '<span class="native-error-line">' . $output[$line-$i] . '</span>';
                }

                $i = 0;

                foreach($output as $key => $l) {
                    $i++;
                    $output[$key] = '<div class="native-line"><span class="native-error-linenumber">' . $i . '</span>' . $l. '</div>';
                }    

                $output = implode(PHP_EOL, $output);
            ?>
            <pre><?=$output?></pre>
        </code>
    <?endif;?>
</div>