<?php
/**
* @package   yoo_explorer Template
* @version   1.5.2 2010-01-03 14:20:02
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// template with same HTML structure like dropdown menu

?>
<ul class="menu menu-dropdown <?php echo $style; ?>">
	<li class="level1 parent separator">
		<span class="level1 parent separator">
			<span class="bg"><?php echo $title; ?></span>
		</span>
		<div class="dropdown columns1" <?php echo $dropdownwidth; ?>>
			<div class="dropdown-t1">
				<div class="dropdown-t2">
					<div class="dropdown-t3"></div>
				</div>
			</div>
			<div class="dropdown-1">
				<div class="dropdown-2">
					<div class="dropdown-3">
						<ul class="col1 level2 first last">
							<li class="level2 item1 first last">
								<div class="group-box1">
									<div class="group-box2">
										<div class="group-box3">
											<div class="group-box4">
												<div class="group-box5">
													<div class="hover-box1">
														<div class="hover-box2">
															<div class="hover-box3">
																<div class="hover-box4">
																	<div class="module"><?php echo $content; ?></div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="dropdown-b1">
				<div class="dropdown-b2">
					<div class="dropdown-b3"></div>
				</div>
			</div>
		</div>
	</li>
</ul>
