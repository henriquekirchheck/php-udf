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
                  postgres = {
                    enable = true;
		    extensions = _: [
                      pkgs.nur.repos.henriquekh.parade-db
		    ];
		    initialScript = "CREATE USER app WITH PASSWORD 'app';";
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
