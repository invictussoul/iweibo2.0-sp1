--
-- 转存表中的数据 `iweibo2_base_config`
--
INSERT INTO `iweibo2_base_config` (`group`, `config`) VALUES('component.viprecommend', 'a:1:{s:6:"people";s:37:"liuxiang\nkarenmo\nkaifulee\nguojingming";}');
INSERT INTO `iweibo2_base_config` (`group`, `config`) VALUES('component.ranking', 'a:1:{s:6:"people";s:107:"liuxiang\nkarenmo\nkaifulee\nguojingming\ncaikangyong\nmayili007\nloveshuqiforever\nmichelejiaxin\nsunyanzi\nhejiong";}');

--
-- 转存表中的数据 `iweibo2_component_banner`
--

INSERT INTO `iweibo2_component_banner` (`id`, `name`, `picture`, `description`, `start_time`, `end_time`, `url`) VALUES(NULL, '南方暴雨', 'http://app.qpic.cn/mblogpic/152ab8bcc951c7458b22/2000', '南方暴雨洪灾已致175人死 或将再迎又一轮强降雨', 1307030400, 1598803200, 'http://t.qq.com/search/index.php?k=%E5%8D%97%E6%96%B9%E6%9A%B4%E9%9B%A8&pos=101');
INSERT INTO `iweibo2_component_banner` (`id`, `name`, `picture`, `description`, `start_time`, `end_time`, `url`) VALUES(NULL, 'iWeibo2.0', 'http://app.qpic.cn/mblogpic/cb91dcbffa0cff4cdf02/2000', 'iWeibo2.0与DISCUZ强强合作', 1308326400, 1598803200, 'http://t.qq.com/api_iweibo');

--
-- 转存表中的数据 `iweibo2_component_brand`
--

INSERT INTO `iweibo2_component_brand` (`id`, `name`, `description`, `picture`, `link`) VALUES(NULL, 'api_iweibo', '腾讯iWeibo产品开发团队', 'http://app.qlogo.cn/mbloghead/b290ce596be363dbcdf6/120', 'http://t.qq.com/api_iweibo');

--
-- 转存表中的数据 `iweibo2_component_hottopic`
--

INSERT INTO `iweibo2_component_hottopic` (`id`, `name`, `description`, `picture`, `picture2`) VALUES(NULL, '上海电影节', '上海电影节闭幕,@韩杰获最佳导演大奖', 'http://app.qpic.cn/mblogpic/a86c6877a623df027f10/160', 'http://app.qpic.cn/mblogpic/6964cd43d5843d2e9940/160');
INSERT INTO `iweibo2_component_hottopic` (`id`, `name`, `description`, `picture`, `picture2`) VALUES(NULL, '不疯狂枉毕业', '毕业季来到,不疯狂不毕业', 'http://app.qpic.cn/mblogpic/63587faec31581981622/160', 'http://app.qpic.cn/mblogpic/cc2887d9dff601fc392c/460?1308562942023');
INSERT INTO `iweibo2_component_hottopic` (`id`, `name`, `description`, `picture`, `picture2`) VALUES(NULL, '南方暴雨', '南方暴雨灾情严重,强降雨仍将持续', 'http://app.qpic.cn/mblogpic/4d05a38835968e4c3592/160', 'http://app.qpic.cn/mblogpic/a1ff48bfd3ced153048c/160');
INSERT INTO `iweibo2_component_hottopic` (`id`, `name`, `description`, `picture`, `picture2`) VALUES(NULL, '国奥0-1负阿曼', '国奥0-1阿曼遗憾告负,冲击伦敦梦未碎', 'http://app.qpic.cn/mblogpic/247abd417cc67e99267a/160', 'http://app.qpic.cn/mblogpic/5a486792c7e4ec54d64e/160');

--
-- 转存表中的数据 `iweibo2_component_management`
--

INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'right', 'fansranking', '1', 3, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'right', 'viprecommend', '1', 2, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'right', 'recommend', '1', 1, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'right', 'hottopic', '1', 0, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'right', 'fansranking', '1', 4, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'main', 'hottopic', '0', 0, '热门话题', 5, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'recommend', '1', 2, '热门用户', 21, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'todayrecommend', '1', 0, '每日推荐', 1, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'recommend', '0', 0, '推荐用户', 6, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'viprecommend', '0', 0, '名人推荐', 8, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'banner', '1', 4, '广告', 2, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'brand', '0', 0, '推荐品牌', 2, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'main', 'hottopic', '0', 0, '热门话题', 5, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'todayrecommend', '0', 0, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'todayrecommend', '0', 0, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'recommend', '0', 0, '推荐用户', 20, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'viprecommend', '0', 0, '名人推荐', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'banner', '1', 2, '广告', 2, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'brand', '0', 0, 'DD', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'hottopic', '0', 0, '热门话题', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'right', 'hottags', '1', 6, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'main', 'todayrecommend', '1', 0, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'main', 'recommend', '0', 4, '推荐用户', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'main', 'viprecommend', '0', 3, '名人推荐', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'main', 'banner', '1', 2, '广告', 2, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 3, 'main', 'brand', '0', 1, '推荐品牌', 5, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'hottopic', '0', 0, '热门话题', 5, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'right', 'brand', '1', 5, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'main', 'todayrecommend', '1', 1, '每日推荐', 1, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'main', 'recommend', '0', 4, '推荐用户', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'main', 'viprecommend', '0', 3, '名人推荐', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'main', 'banner', '1', 2, '广告', 3, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 4, 'main', 'brand', '0', 1, '推荐品牌', 3, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'main', 'hottopic', '1', 0, '热门话题', 4, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'right', 'fansranking', '1', 4, '人气排行塝', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'main', 'todayrecommend', '1', 0, '每日推荐', 1, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'main', 'recommend', '0', 0, '推荐用户', 20, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'main', 'viprecommend', '0', 0, '名人推荐', 20, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'main', 'banner', '1', 0, '广告', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'main', 'brand', '0', 0, '品牌', 20, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'brand', '0', 0, '推荐品牌', 5, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'right', 'viprecommend', '1', 3, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'main', 'todayrecommend', '1', 1, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'main', 'recommend', '0', 4, '推荐用户', 2, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'main', 'viprecommend', '0', 3, '名人推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'main', 'banner', '1', 2, '广告', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'main', 'brand', '0', 1, '推荐品牌', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'banner', '0', 0, '广告', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'right', 'recommend', '1', 2, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'main', 'todayrecommend', '1', 0, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'main', 'recommend', '0', 2, '推荐用户', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'main', 'viprecommend', '0', 3, '名人推荐', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'main', 'banner', '1', 1, '广告', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'main', 'brand', '0', 0, '推荐品牌', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'viprecommend', '1', 1, '推荐名人', 4, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'right', 'hottopic', '1', 1, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'main', 'todayrecommend', '0', 0, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'main', 'recommend', '0', 0, '推荐用户', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'main', 'viprecommend', '0', 0, '名人推荐', 6, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'main', 'banner', '0', 0, '广告', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 8, 'main', 'brand', '0', 0, '推荐品牌', 5, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'hottopic', '0', 0, '热门话题', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 7, 'main', 'hottopic', '0', 0, '热门话题', 3, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 5, 'main', 'hottopic', '0', 0, '热门话题', 5, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 6, 'main', 'hottopic', '0', 0, '热门话题', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 1, 'main', 'todayvip', '0', 0, '今日名人', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 2, 'main', 'todayvip', '0', 0, '今日名人', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'main', 'todayrecommend', '0', 0, '每日推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'main', 'recommend', '0', 0, '推荐用户', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'main', 'viprecommend', '0', 0, '名人推荐', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'main', 'banner', '0', 0, '广告', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'main', 'hottopic', '0', 0, '热门话题', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'main', 'todayvip', '0', 0, '今日名人', 20, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'right', 'hottopic', '1', 0, '热门话题', 3, 3);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'right', 'recommend', '1', 0, '推荐用户', 9, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'right', 'viprecommend', '1', 0, '名人推荐', 3, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'right', 'fansranking', '1', 0, '人气排行榜', 10, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'right', 'brand', '1', 0, '推荐品牌', 1, 2);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 10, 'right', 'hottags', '1', 0, '热门标签', 15, 1);
INSERT INTO `iweibo2_component_management` (`id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, `component_title`, `component_number`, `component_style`) VALUES(NULL, 9, 'main', 'todayvip', '0', 0, '今日名人', 20, 1);

--
-- 转存表中的数据 `iweibo2_component_recommend`
--

INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'zhangxiaoxian', '张小娴，香港著名作家。其作品既描写了世俗男女的爱情生活，又深藏了对人性的精致刻画，开两岸三地一代言情文风。从她的第一部作品《面包树上的女人》到新作《想念》，张小娴已成为香港流行文化的文字符号，影响甚至改变了年轻一代的爱情观。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'real_sunnan', '孙楠，男，1969年出生，中国大陆实力派歌手。代表作主要有《不见不散》《你快回来》《红旗飘飘》等。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'wuxinxin', '吴昕，女，湖南卫视《快乐大本营》主持人。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'sunzhengping0228', '孙正平，中央电视台著名体育播音员以及解说员。1992年被聘为主任播音员，曾任体育部专题组组长，现任体育中心播音组组长。2008年12月25日获中国播音主持金话筒奖。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'hejiong', '何炅，男，北京外国语大学阿拉伯语系教师，《快乐大本营》主持人.');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'jiajingwen', '贾静雯，女，台湾演员与节目主持人。担任台语剧《七世夫妻——梁山伯与祝英台》女主角，后出演《飞龙在天》奠定台湾八点档天后、戏剧一姐地位。不久之后，进入内地娱乐市场，出演《大汉天子》《倚天屠龙记》《至尊红颜》《秦王李世民》等片。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'xujinglei', '徐静蕾，女，国内著名影视演员、导演，有影视圈才女之称。 代表作：《将爱情进行到底》、《一个陌生女人的来信》、《伤城》、《杜拉拉升职记 》');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'linxinru', '林心如，女，台湾演员，享誉国际的当红一线女星，最具人气、美誉度，观众最喜爱的华人女演员。主要影视作品有：《还珠格格》、《新三国》、《美人心计》、《情深深雨蒙蒙》、《男才女貌》等。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'wuyanzu', '吴彦祖，男，香港著名演员。在2004年的金马奖，吴彦祖以《新警察故事》赢得最佳男配角奖。以及凭《四大天王》一片，于第26届香港电影金像奖中夺得新晋导演奖。2010年4月6日，');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'haiqing', '海清，女，演员，毕业于北京电影学院，著名影视剧演员,被称为“媳妇专业户”。在电视剧《蜗居》中海萍更是大红大紫。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'karenmo', '莫文蔚，女，香港影、视、歌多栖明星。电影代表作包括：《大话西游》、《色情男女》、《食神》、《心动》、《杜拉拉升职记》等。发表过多张专辑并凭借专辑《[i]》获得第14届台湾金曲奖最佳国语女演唱人奖。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'mayili007', '马伊琍，女，内地演员，毕业于上海戏剧学院表演系本科班，曾在多部电影、电视剧中出演女主角，2008年凭借影片《江北好人》， 马伊琍战胜了李冰冰、范冰冰两位大热候选人，成为了长春电影节影后。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'kaifulee', '创新工场董事长兼首席执行官');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'caikangyong', '蔡康永，台湾著名节目主持人、作家。其名人访谈节目《真情指数》和青老年人沟通节目《两代电力公司》、综艺访谈节目《康熙来了》家喻户晓。曾多次主持金马奖颁奖典礼。出版过多本散文著作，包括《痛快日记》、《LA流浪记》和《那些男孩教我的事》等畅销作品。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'guojingming', '郭敬明，男，著名作家。上海最世文化发展有限公司董事长，《最小说》、《最漫画》杂志主编，长江文艺出版社北京图书中心副总编辑。著有《幻城》、《梦里花落知多少》、《小时代》、《爵迹》等多部畅销作品。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'liuxiang', '刘翔，2011年上海田径大奖赛使用七步上栏新技术，以13秒07的成绩获男子110米栏金牌；2010广州亚运会金牌；04年雅典奥运会金牌。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'loveshuqiforever', '舒淇，女，香港演员，原名林立慧。1996年从台湾到香港发展，代表作品有：《非诚勿扰》、《韩城攻略》等。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'michelejiaxin', '李嘉欣，女，香港著名艺人。18岁摘得港姐后冠，一路走来“美”不可挡，一直占据“最美丽港姐”头衔。拍摄多部影视剧，2009年与香港富商许晋亨成婚。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'sunyanzi', '孙燕姿，女，新加坡歌手。被视为华语歌坛天后，而至今的成就造就了畅销天后的美誉，受奖无数。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'bishumin', '女，著名作家，全国政协委员，著有《红处方》、《血玲珑》、《预约死亡》、《鲜花手术》等多部畅销书。');
INSERT INTO `iweibo2_component_recommend` (`id`, `account`, `description`) VALUES(NULL, 'yangmiblog0912', '杨幂，女，内地女演员。在09年4月的“80后新生代娱乐大明星”评选活动中，杨幂成为内地新“四小花旦”（与黄圣依、王珞丹以及刘亦菲）之首。06年因出演《神雕侠侣》中郭襄而受到观众的关注，2009年又凭借《仙剑奇侠传三》而令她人气飙升。');

--
-- 转存表中的数据 `iweibo2_mb_tag`
--

INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, 'IT民工', '000000', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '香水', '276CE3', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '女王', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '宅女', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '游戏', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '不疯魔不成活', '4019FF', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '美剧', '9575FF', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '高跟鞋', '1CA4FF', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '成熟', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '奋斗', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '乐活族', '000000', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '月光族', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '喜欢你没道理', '0D7EFF', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '90后', '', 0, 1);
INSERT INTO `iweibo2_mb_tag` (`id`, `tagname`, `color`, `usenum`, `visible`) VALUES(NULL, '我的青春我做主', '', 0, 1);

--
-- 转存表中的数据 `iweibo2_mb_today_recommend`
--

INSERT INTO `iweibo2_mb_today_recommend` (`id`, `content`, `displayorder`) VALUES(NULL, '<a href="http://t.qq.com/p/t/21583111581404" target="_blank" style="color:#E57600;">@腾讯娱乐</a>：“<a href="http://t.qq.com/le-jia" target="_blank" style="color:#E57600;">@乐嘉</a>请辞江苏卫视 《非诚勿扰》暂不受影响。”', 0);

--
-- 转存表中的数据 `iweibo2_user_tiview`
--

INSERT INTO `iweibo2_user_tiview` (`id`, `tname`, `notice`, `style`, `host`, `desc`, `direct`, `sdate`, `edate`, `dateline`) VALUES(1, '男篮微访谈', 0, 'a:7:{s:7:"outward";s:0:"";s:5:"cover";s:0:"";s:10:"background";s:0:"";s:6:"repeat";i:0;s:7:"bgcolor";s:0:"";s:9:"linkcolor";s:0:"";s:3:"vod";s:1:"0";}', 'weibotalk', '东亚男篮锦标赛正在南京如火如荼的举行，中国队在击败卫冕冠军韩国队之后挺进半决赛将对阵日本。腾讯体育邀请到了男篮三名球员于澍龙、孟铎、张博做客微访谈，想了解中国男篮更多？快上腾讯微博来提问吧！', 0, 1308142620, 1309438620, 1308661193);

--
-- 转存表中的数据 `iweibo2_user_tiview_join`
--

INSERT INTO `iweibo2_user_tiview_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'zhangbo81', 1, 1308661193);
INSERT INTO `iweibo2_user_tiview_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'yushulong219', 1, 1308661193);
INSERT INTO `iweibo2_user_tiview_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'weibotalk', 0, 1308661193);
INSERT INTO `iweibo2_user_tiview_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'CBAmengduo', 1, 1308661193);

--
-- 转存表中的数据 `iweibo2_user_tlive`
--

INSERT INTO `iweibo2_user_tlive` (`id`, `tname`, `title`, `notice`, `style`, `host`, `desc`, `direct`, `sdate`, `edate`, `dateline`) VALUES(1, '腾讯合作伙伴大会', '腾讯合作伙伴大会', 0, 'a:7:{s:7:"outward";s:0:"";s:5:"cover";s:0:"";s:10:"background";s:0:"";s:6:"repeat";i:0;s:7:"bgcolor";s:0:"";s:9:"linkcolor";s:0:"";s:3:"vod";s:1:"0";}', 'chenweihong', '腾讯在北京召开合作伙伴大会，和所有第三方合作伙伴共商开放大计。马化腾发表演讲提出面对互联网未来的“八个选择”，并宣布腾讯要打造一个规模最大、最成功的开放平台，扶持所有合作伙伴再造一个腾讯。', 0, 1308142080, 1309438080, 1308660436);

--
-- 转存表中的数据 `iweibo2_user_tlive_join`
--

INSERT INTO `iweibo2_user_tlive_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'martinlau', 1, 1308660885);
INSERT INTO `iweibo2_user_tlive_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'kaifulee', 1, 1308660885);
INSERT INTO `iweibo2_user_tlive_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'hyxing', 1, 1308660885);
INSERT INTO `iweibo2_user_tlive_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'huyanping', 1, 1308660885);
INSERT INTO `iweibo2_user_tlive_join` (`id`, `tid`, `uname`, `utype`, `dateline`) VALUES(NULL, 1, 'chenweihong', 0, 1308660885);

