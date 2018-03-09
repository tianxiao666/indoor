package com.dao.common;

public class MathUtil {
	/**
	 * 计算两个点之间的距离
	 */
	public double getDistanceForTwoPoint(double x1, double y1, double x2,
			double y2) {
		return (Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2)));
	}

	/**
	 * 一元二次方程deta
	 */
	public double getDeta(double a, double b, double c) {
		return (b * b - 4 * a * c);
	}

	/**
	 * Unitary quadratic equation 获取一元二次方程的解
	 * 
	 * @param a
	 *            不能为0
	 */
	public PointD getUQESolution(double a, double b, double c) {
		if (a != 0) {
			PointD p = new PointD();
			double deta = getDeta(a, b, c);
			if (deta >= 0) {
				p.x = (-b + Math.sqrt(deta)) / (2 * a);
				p.y = (deta == 0) ? p.x : ((-b - Math.sqrt(deta)) / (2 * a));
				return (p);
			}
		}
		return (null);
	}

	/**
	 * 计算两个圆的交点
	 * 
	 * 设w=(r1*r1-r2*r2-a1*a1+a2*a2-b1*b1+b2*b2+2*b1-2*b2)/(2*a2-2*a1)
	 * 则计算两个圆交点的方程转换为x = wy 带入方程得到(w*w+1)*y*y -
	 * (2*a1*e+2*b1)*y+(a1*a1+b1*b1-r1*r1)=0 然后根据一元二次方程的求解
	 * 
	 * @param r1
	 *            圆1的半径
	 * @param a1
	 *            圆1坐标x,不能等于a2
	 * @param b1
	 *            圆1坐标y
	 * @param r2
	 *            圆2的半径
	 * @param a2
	 *            圆2坐标x
	 * @param b2
	 *            圆2坐标y
	 * @return 返回两个圆交点的坐标
	 */
	public PointD[] getIntersectionForTwoCircle(double r1, double a1,
			double b1, double r2, double a2, double b2) {
		if (a1 != a2) {
			// (x-a1)*(x-a1)+(y-b1)*(y-b1)=r1*r1
			// (x-a2)*(x-a2)+(y-b2)*(y-b2)=r2*r2
			// 下式减上式得
			// x = v * y + w
			double w = (r1 * r1 - r2 * r2 - a1 * a1 + a2 * a2 - b1 * b1 + b2
					* b2)
					/ (2 * (a2 - a1));
			double v = (b1 - b2) / (a2 - a1);
			double a = (v * v + 1);
			double b = (2 * w * v - 2 * a1 * v - 2 * b1);
			double c = (w * w - r1 * r1 + a1 * a1 - 2 * a1 * w + b1 * b1);
			// a*y*y+b*y+c=0
			PointD p = new MathUtil().getUQESolution(a, b, c);
			if (p != null) {
				PointD[] intersections = new PointD[(p.x != p.y) ? 2 : 1];
				intersections[0] = new PointD();
				// 第一个交点
				intersections[0].y = p.x;
				intersections[0].x = w + v * intersections[0].y;
				if (p.x != p.y) {
					intersections[1] = new PointD();
					// 第二个交点
					intersections[1].y = p.y;
					intersections[1].x = w + v * intersections[1].y;
				}
				return (intersections);
			}
		}
		return (null);
	}

	/**
	 * 计算两个圆的交点组成的直线，与另外一个圆的交点
	 * 
	 * @param r
	 *            第三个圆的半径
	 * @param r_x
	 *            第三个圆坐标x
	 * @param r_y
	 *            第三个圆坐标y
	 * @param x0
	 *            直线的两个点x,不能等于x1
	 * @param y0
	 *            直线的两个点y
	 * @param x1
	 *            直线的两个点x
	 * @param y1
	 *            直线的两个点y
	 */
	public PointD[] getIntersectionForCircleAndLine(double r, double r_x,
			double r_y, double x0, double y0, double x1, double y1) {
		if (x0 != x1) {
			// y=kx+b
			double k = (y0 - y1) / (x0 - x1);
			double b = y0 - k * x0;
			return (getIntersectionForCircleAndLine(r, r_x, r_y, k, b));
		}
		return (null);
	}

	public PointD[] getIntersectionForCircleAndLine(double r, double r_x,
			double r_y, double k, double b) {
		double a1 = 1 + k * k;
		double b1 = -2 * (r_x + k * r_y - k * b);
		double c1 = r_x * r_x + r_y * r_y - 2 * b * r_y - r * r + b * b;
		PointD p = new MathUtil().getUQESolution(a1, b1, c1);
		if (p != null) {
			PointD[] intersections = new PointD[(p.x != p.y) ? 2 : 1];
			intersections[0] = new PointD();
			// 第一个交点
			intersections[0].x = p.x;
			intersections[0].y = k * intersections[0].x + b;
			if (p.x != p.y) {
				intersections[1] = new PointD();
				// 第二个交点
				intersections[1].x = p.y;
				intersections[1].y = k * intersections[1].x + b;
			}
			return (intersections);
		}
		return null;
	}
}