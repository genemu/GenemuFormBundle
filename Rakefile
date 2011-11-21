task :test do
    if ENV["TRAVIS"] == 'true'
        puts "Travis CI"

        system "wget http://pecl.php.net/get/intl-1.1.2.tgz"
        system "pear install intl-1.1.2.tgz"
    end

    puts "Testing default system"
    system "phpunit"
end

task :release, :version do |t, args|
    version = args[:version]

    Rake::Task["test"]
end
