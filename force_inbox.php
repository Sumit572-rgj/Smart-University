<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/message/index.php';
$c = file_get_contents($f);

$start = strpos($c, '<div id="inbox"');
$end = strpos($c, '<div id="forums"');

$oldTabs = substr($c, $start, $end - $start);

$newTabs = <<<HTML
<div id="inbox" class="tab-content">
                        <?php if(empty(\$received)): ?>
                            <div class="p-6 text-gray-500 text-center">Your inbox is empty.</div>
                        <?php endif; ?>
                        <?php foreach(\$received as \$msg): ?>
                            <div class="p-4 border-b border-gray-100 <?= \$msg['is_read'] ? 'bg-white' : 'bg-blue-50' ?>">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <strong class="text-gray-900"><?= htmlspecialchars(\$msg['sender_name']) ?></strong>
                                        <span class="text-xs text-gray-500 ml-2"><?= htmlspecialchars(date('M d, g:i A', strtotime(\$msg['created_at']))) ?></span>
                                    </div>
                                    <div class="flex gap-2">
                                        <?php if(!\$msg['is_read']): ?>
                                            <a href="/cit_ums/message?read=<?= \$msg['id'] ?>" class="text-xs text-blue-600 hover:underline font-bold">Mark as Read</a>
                                        <?php endif; ?>
                                        <form action="/cit_ums/message" method="POST" onsubmit="return confirm('Delete this message?');">
                                            <input type="hidden" name="delete_message" value="<?= \$msg['id'] ?>">
                                            <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <h4 class="font-bold text-md mb-1 cursor-pointer hover:text-blue-600" onclick="document.getElementById('msg-body-recv-<?= \$msg['id'] ?>').style.display = document.getElementById('msg-body-recv-<?= \$msg['id'] ?>').style.display === 'none' ? 'block' : 'none';">
                                    <?= htmlspecialchars(\$msg['subject']) ?> <span class="text-xs text-gray-400 font-normal ml-2">(Click to open)</span>
                                </h4>
                                <div id="msg-body-recv-<?= \$msg['id'] ?>" style="display:none;" class="mt-2 bg-white p-3 rounded border border-gray-100">
                                    <p class="text-gray-700 text-sm whitespace-pre-wrap mb-2"><?= htmlspecialchars(\$msg['body']) ?></p>
                                    <?php if(\$msg['attachment_path']): ?>
                                        <a href="<?= htmlspecialchars(\$msg['attachment_path']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">View Attachment</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="sent" class="tab-content" style="display:none;">
                        <?php if(empty(\$sent)): ?>
                            <div class="p-6 text-gray-500 text-center">No sent messages.</div>
                        <?php endif; ?>
                        <?php foreach(\$sent as \$msg): ?>
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="text-gray-500">To:</span> <strong class="text-gray-900"><?= htmlspecialchars(\$msg['receiver_name']) ?></strong>
                                        <span class="text-xs text-gray-500 ml-2"><?= htmlspecialchars(date('M d, g:i A', strtotime(\$msg['created_at']))) ?></span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-xs <?= \$msg['is_read'] ? 'text-green-500' : 'text-gray-400' ?>"><?= \$msg['is_read'] ? 'Read' : 'Delivered' ?></span>
                                        <form action="/cit_ums/message" method="POST" onsubmit="return confirm('Delete this message?');">
                                            <input type="hidden" name="delete_message" value="<?= \$msg['id'] ?>">
                                            <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <h4 class="font-bold text-md mb-1 cursor-pointer hover:text-blue-600" onclick="document.getElementById('msg-body-sent-<?= \$msg['id'] ?>').style.display = document.getElementById('msg-body-sent-<?= \$msg['id'] ?>').style.display === 'none' ? 'block' : 'none';">
                                    <?= htmlspecialchars(\$msg['subject']) ?> <span class="text-xs text-gray-400 font-normal ml-2">(Click to open)</span>
                                </h4>
                                <div id="msg-body-sent-<?= \$msg['id'] ?>" style="display:none;" class="mt-2 bg-gray-50 p-3 rounded border border-gray-100">
                                    <p class="text-gray-700 text-sm whitespace-pre-wrap mb-2"><?= htmlspecialchars(\$msg['body']) ?></p>
                                    <?php if(\$msg['attachment_path']): ?>
                                        <a href="<?= htmlspecialchars(\$msg['attachment_path']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">View Attachment</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    
HTML;

$c = str_replace($oldTabs, $newTabs, $c);
file_put_contents($f, $c);
echo "Tabs updated!";
