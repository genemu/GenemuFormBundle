require 'date'
require 'digest/md5'
require 'fileutils'

task :test do
    if ENV["TRAVIS"] == 'true'
        puts "Travis CI"

        system "sudo apt-get install -y php5-gd"

        system "wget http://pecl.php.net/get/intl-1.1.2.tgz"
        system "tar -xzf intl-1.1.2.tgz"
        system "cd intl-1.1.2 && phpize && ./configure --enable-intl && make && sudo make install"

        system "echo 'extension=intl.so' >> `php --ini | grep 'Loaded Configuration' | sed -e 's|.*:\s*||'"
        system "echo 'extension=gd.so' >> `php --ini | grep 'Loaded Configuration' | sed -e 's|.*:\s*||'"
    end

    puts "Testing default system"
    system "phpunit"
end

task :release, :version do |t, args|
    version = args[:version]

    Rake::Task["test"]
end
