# treefmt.nix
{ pkgs, ... }:
{
  # Used to find the project root
  projectRootFile = "flake.nix";
  programs.prettier.enable = true;
  programs.php-cs-fixer.enable = true;
  programs.nixfmt.enable = true;
}
