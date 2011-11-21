require 'date'
require 'digest/md5'
require 'fileutils'

task :test do
    if ENV["TRAVIS"] == 'true'
        puts "Travis CI"

        system "sudo apt-get install -y php5-gd php5-intl"

        system "echo 'extension=intl.so' >> `php --ini | grep 'Loaded Configuration' | sed -e 's|.*:\s*||`"
        system "echo 'extension=gd.so' >> `php --ini | grep 'Loaded Configuration' | sed -e 's|.*:\s*||`"
    end

    puts "Testing default system"
    system "phpunit"
end

task :release, :version do |t, args|
    version = args[:version]

    Rake::Task["test"]
end
