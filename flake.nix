{
  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
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
    }@inputs:
    flake-utils.lib.eachDefaultSystem (
      system:
      let
        pkgs = import nixpkgs { inherit system; };
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
                languages.php = {
                  enable = true;
                  # fpm.pools.caddy = {
                  #   settings = {
                  #     "pm" = "dynamic";
                  #     "pm.max_children" = 75;
                  #     "pm.start_servers" = 10;
                  #     "pm.min_spare_servers" = 5;
                  #     "pm.max_spare_servers" = 20;
                  #     "pm.max_requests" = 500;
                  #   };
                  # };
                };

                services = {
                  caddy = {
                    enable = true;
                    package = pkgs.frankenphp;
                    config = ''
                      {
                        frankenphp
                        order php_server before file_server
                      }
                    '';
                    virtualHosts."http://localhost:8000" = {
                      extraConfig = ''
                        root * ${config.devenv.root}/public
                        encode zstd br gzip
                        php_server
                      '';
                    };
                  };
                  mysql = {
                    enable = true;
                    ensureUsers = [
                      {
                        name = "admin";
                        password = "admin";
                        ensurePermissions."*.*" = "ALL PRIVILEGES";
                      }
                    ];
                    initialDatabases = [
                      {
                        name = "app";
                        schema = ./src/schema.sql;
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
