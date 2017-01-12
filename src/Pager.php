<?php

namespace Thinfer;

/**
 * PHP-简易分页类
 *
 * @author Barry
 */
class Pager
{
    /**
     * @var const 页码占位符
     */
    const PLACEHOLDER = '<page>';

    /**
     * @var const 数字按钮个数上限，必须为奇数且不小于3
     */
    const BUTTON_NUM = 7;

    /**
     * 生成分页主方法
     * @param int $page 当前页码
     * @param int $pageSize 每页个数
     * @param int $count 总个数
     * @param string $url 分页链接原型，必须包含页码占位符，例如：http://foo.com/bar/baz-<page>.html
     * @return string html
     */
    public static function generate($page, $pageSize, $count, $url)
    {
        if ($pageSize >= $count) {
            return '';
        }
        $total = ceil($count / $pageSize);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > $total) {
            $page = $total;
        }
        $le = $re = false;
        $bl = self::BUTTON_NUM;
        if ($total <= $bl) {
            $range = [1, $total];
        } else {
            if ($page < ($bl + 1) / 2 + 1) {
                $range = [1, $bl - 1];
                $re = true;
            } elseif ($page > $total - ($bl + 1) / 2) {
                $le = true;
                $range = [$total - $bl + 2, $total];
            } else {
                $le = $re = true;
                $range = [$page - ($bl - 3) / 2, $page + ($bl - 3) / 2];
            }
        }

        list($pre, $suf) = explode(self::PLACEHOLDER, $url);
        $html = '';
        if ($page > 1) {
            $html .= '<a href="'.$pre.($page - 1).$suf.'">上一页</a>';
        }
        if ($le) {
            $html .= '<a href="'.$pre.'1'.$suf.'">1</a><span class="ell">...</span>';
        }
        for ($i = $range[0]; $i <= $range[1]; $i++) {
            if ($page == $i) {
                $html .= '<span class="cur">'.$i.'</span>';
            } else {
                $html .= '<a href="'.$pre.$i.$suf.'">'.$i.'</a>';
            }
        }
        if ($re) {
            $html .= '<span class="ell">...</span><a href="'.$pre.$total.$suf.'">'.$total.'</a>';
        }
        if ($page < $total) {
            $html .= '<a href="'.$pre.($page + 1).$suf.'">下一页</a>';
        }

        return $html;
    }
}
