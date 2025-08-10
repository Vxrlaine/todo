import 'package:flutter/material.dart';

class CustomAppBar extends StatelessWidget implements PreferredSizeWidget {
  final String title;
  final List<Widget>? actions;
  final Color backgroundColor;
  final double elevation;
  final ShapeBorder? shape;

  const CustomAppBar({
    Key? key,
    required this.title,
    this.actions,
    this.backgroundColor = const Color(0xffff8fab),
    this.elevation = 4,
    this.shape,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      backgroundColor: backgroundColor,
      elevation: elevation,
      shape: shape,
      title: Text(
        title,
        style: const TextStyle(fontWeight: FontWeight.bold),
      ),
      actions: actions,
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}
