{
  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
    nur = {
      url = "github:nix-community/NUR";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    flake-utils.url = "github:numtide/flake-utils";
    devenv = {
      url = "github:cachix/devenv";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    treefmt-nix = {
      url = "github:numtide/treefmt-nix";
      inputs.nixpkgs.follows = "nixpkgs";
    };
  };

  outputs =
    {
      self,
      nixpkgs,
      flake-utils,
      devenv,
      treefmt-nix,
      nur,
    }@inputs:
    flake-utils.lib.eachDefaultSystem (
      system:
      let
        pkgs = import nixpkgs { inherit system overlays; };
        overlays = [ nur.overlays.default ];
        treefmt' = treefmt-nix.lib.evalModule pkgs ./treefmt.nix;
      in
      {
        formatter = treefmt'.config.build.wrapper;
        checks.formatting = treefmt'.config.build.check self;
        devShells.default = devenv.lib.mkShell {
          inherit inputs pkgs;
          modules = [
            (
              { pkgs, config, ... }:
              {
                packages = with pkgs; [ pgcli ];

                languages.php.enable = true;

                services = {
                  caddy = {
                    enable = true;
                    package = pkgs.frankenphp;
                    config = ''
                      {
                        frankenphp
                        order php_server before file_server
                        admin localhost:8001
                      }
                    '';
                    virtualHosts."http://localhost:8000" = {
                      extraConfig = ''
                        root * ${config.devenv.root}/public
                        encode zstd br gzip
                        php_server {
                          try_files {path} {path}/index.php =404
                        }
                      '';
                    };
                  };
                  postgres = {
                    enable = true;
                    extensions = _: [
                      pkgs.nur.repos.henriquekh.parade-db
                    ];
                    listen_addresses = "localhost";
                    initialDatabases = [
                      {
                        name = "app";
                        schema = ./src/schema.sql;
                        user = "app";
                        pass = "app";
                        initialSQL = "CREATE EXTENSION IF NOT EXISTS pg_search;";
                      }
                    ];
                  };
                };
              }
            )
          ];
        };
      }
    );
}
